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
                'value' => 3,
                'type' => 'number',
                'description' => "Minimum number of items to checkout",
            ],
            [
                'key' => "minimum price",
                'value' => 1000,
                'type' => 'number',
                'description' => "Minimum price to checkout",
            ],
            [
                'key' => 'activate vendor',
                'value' => 0,
                'type' => 'select',
                'description' => "To display the vendor in the front pages",
            ],
            [
                'key' => 'show become a rep in the menu',
                'value' => 0,
                'type' => 'select',
                'description' => "To display the become a rep in the front pages menu header",
            ],
            [
                'key' => 'main logo',
                'value' => asset('assets/img/logo.webp'),
                'type' => 'image',
                'description' => "Main logo of the website",
            ]

        ];

        foreach ($values as $value) {
            $publicValue = PublicSetting::where('key', $value['key'])->first();
            if (!$publicValue) {
                PublicSetting::create($value);
            }
        }
    }
}
