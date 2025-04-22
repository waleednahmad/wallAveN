<?php

namespace Database\Seeders;

use App\Models\PageBreadcrump;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageBreadcrumpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [

            [
                'name' => 'shop',
                'title' => 'Shop',
            ],
        ];

        foreach ($pages as $page) {
            $pageName = PageBreadcrump::where('name', $page['name'])->first();
            if (!$pageName) {
                PageBreadcrump::create($page);
            }
        }
    }
}
