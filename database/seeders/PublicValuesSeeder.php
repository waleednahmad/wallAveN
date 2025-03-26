<?php

namespace Database\Seeders;

use App\Models\PublicSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublicValuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $table->string('key')->unique();
        // $table->text('value')->nullable();
        // $table->string('type')->default('string');
        // $table->string('description')->nullable();
        $values = [
            [
                'key' => "minimum items",
                'value' => 1,
                'type' => 'number',
                'description' => "Minimum number of items to checkout",
            ],
            [
                'key' => "minimum price",
                'value' => 100,
                'type' => 'number',
                'description' => "Minimum price to checkout",
            ],
            [
                'key' => 'activate vendor',
                'value' => 0,
                'type' => 'select',
                'description' => "To display the vendor in the front pages",
            ],
            // show become a rep in the menu
            [
                'key' => 'show become a rep in the menu',
                'value' => 0,
                'type' => 'select',
                'description' => "To display the become a rep in the front pages menu header",
            ],
            //main logo
            [
                'key' => 'main logo',
                'value' => asset('assets/img/logo.webp'),
                'type' => 'image',
                'description' => "Main logo of the website",
            ],
            // show category & shop pages
            [
                'key' => 'toggle show category & shop pages',
                'value' => 1,
                'type' => 'select',
                'description' => "To display the category & shop pages in the front pages",
            ],
            // google analytics
            [
                'key' => 'google analytics',
                'value' => '<script async src="https://www.googletagmanager.com/gtag/js?id=G-CVGZF5CQ6D"></script>
                <script>
                    window.dataLayer = window.dataLayer || [];

                    function gtag() {
                        dataLayer.push(arguments);
                    }
                    gtag("js", new Date());

                    gtag("config", "G-CVGZF5CQ6D");
                </script>',
                'type' => 'text',
                'description' => "Google analytics code",
            ],
            // favicon
            [
                'key' => 'favicon',
                'value' => asset('assets/img/favicon.png'),
                'type' => 'image',
                'description' => "Favicon of the website",
            ],
            // website title
            [
                'key' => 'website title',
                'value' => 'Golden Rugs',
                'type' => 'text',
                'description' => "Title of the website",
            ],

        ];

        foreach ($values as $value) {
            $publicValue = PublicSetting::where('key', $value['key'])->first();
            if (!$publicValue) {
                PublicSetting::create($value);
            }
        }
    }
}
