<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

/**
 * robots.txt and sitemap.xml, built from the store's own settings so every company
 * gets the right domain and only the pages its visitors can actually open.
 */
class SeoController extends Controller
{
    public function robots()
    {
        $lines = [
            'User-agent: *',
            // Accounts and back office: nothing for search engines there
            'Disallow: /super_admin',
            'Disallow: /dealer/',
            'Disallow: /representative',
            'Disallow: /livewire',
            'Disallow: /login',
            '',
            'Sitemap: ' . route('sitemap'),
        ];

        return response(implode("\n", $lines) . "\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function sitemap()
    {
        $mode = catalogMode();

        // Rebuilt at most once an hour, and whenever the catalog visibility changes
        $xml = Cache::remember('sitemap.xml.' . $mode, now()->addHour(), function () use ($mode) {
            $urls = [
                ['loc' => route('frontend.home'), 'priority' => '1.0'],
                ['loc' => route('frontend.aboutUs'), 'priority' => '0.6'],
                ['loc' => route('frontend.contactUs'), 'priority' => '0.6'],
                ['loc' => route('frontend.register'), 'priority' => '0.7'],
            ];

            // Products only when visitors may open them (see the "Catalog for visitors" setting)
            if ($mode !== 'closed') {
                $urls[] = ['loc' => route('frontend.shop'), 'priority' => '0.9'];
                Product::active()->whereNotNull('slug')->where('slug', '!=', '')
                    ->orderByDesc('updated_at')
                    ->get(['slug', 'updated_at', 'image'])
                    ->each(function ($p) use (&$urls) {
                        $urls[] = [
                            'loc' => route('frontend.product', $p->slug),
                            'lastmod' => $p->updated_at?->toAtomString(),
                            'priority' => '0.8',
                            'image' => $p->image ? asset($p->image) : null,
                        ];
                    });
            }

            $out = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
                . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";
            foreach ($urls as $u) {
                $out .= '  <url><loc>' . e($u['loc']) . '</loc>'
                    . (!empty($u['lastmod']) ? '<lastmod>' . $u['lastmod'] . '</lastmod>' : '')
                    . '<priority>' . $u['priority'] . '</priority>'
                    . (!empty($u['image']) ? '<image:image><image:loc>' . e($u['image']) . '</image:loc></image:image>' : '')
                    . "</url>\n";
            }

            return $out . "</urlset>\n";
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
