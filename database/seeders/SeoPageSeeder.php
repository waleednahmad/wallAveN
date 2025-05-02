<?php

namespace Database\Seeders;

use App\Models\SeoPage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeoPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seoPages = [
            [
                'name' => 'home'
            ],
            [
                'name' => 'about_us',
            ],
            [
                'name' => 'contact_us'
            ],
            [
                'name' => 'shop'
            ],
        ];

        foreach ($seoPages as $seoPage) {
            if (!SeoPage::where('name', $seoPage['name'])->exists()) {
                SeoPage::create($seoPage);
            }
        }
    }
}
