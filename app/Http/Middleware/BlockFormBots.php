<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cheap protection for public sign-up forms (dealer and sales rep applications), which were
 * filling up with bot accounts. Pair with @include('partials.form-guard') inside the <form>.
 *  - honeypot: a field people never see; bots fill it
 *  - timing: real people take more than a few seconds to fill a form
 * Bots are sent back to the home page with a neutral message so they learn nothing.
 */
class BlockFormBots
{
    private const MIN_SECONDS = 3;

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('post')) {
            $reason = null;

            if (filled($request->input('contact_me_by_fax_only'))) {
                $reason = 'honeypot';
            } else {
                try {
                    $startedAt = (int) Crypt::decryptString((string) $request->input('form_started_at'));
                    if (time() - $startedAt < self::MIN_SECONDS) {
                        $reason = 'too fast';
                    }
                } catch (\Throwable $e) {
                    $reason = 'missing or tampered start time';
                }
            }

            if ($reason) {
                Log::info('Blocked a likely bot form submission', ['reason' => $reason, 'path' => $request->path(), 'ip' => $request->ip()]);
                return redirect()->route('frontend.home')->with('success', 'Thank you. We will be in touch.');
            }
        }

        return $next($request);
    }
}
