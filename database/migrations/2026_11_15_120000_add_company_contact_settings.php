<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Company contact details as store settings (shown in the header, footer, contact page and emails),
 * so each company edits its own instead of having them written into the templates.
 * Rows are only added when missing; existing values are never changed.
 */
return new class extends Migration
{
    private array $settings = [
        ['key' => 'company phone', 'value' => '(773) 490-3801', 'description' => 'Phone shown in the header, footer, contact page and emails'],
        ['key' => 'company email', 'value' => 'sales@goldenrugsinc.com', 'description' => 'Email shown on the site and in emails'],
        ['key' => 'company address', 'value' => '4528 W 51st St, Chicago, IL 60632', 'description' => 'Full address, used in emails'],
        ['key' => 'company city', 'value' => 'Chicago, IL', 'description' => 'Short location shown on the home and contact pages'],
        ['key' => 'opening hours mon-sat', 'value' => '9:00 am – 7:30 pm', 'description' => 'Opening hours Monday to Saturday (leave empty to hide)'],
        ['key' => 'opening hours sunday', 'value' => '9:30 am – 5:30 pm', 'description' => 'Opening hours on Sunday (leave empty to hide)'],
    ];

    public function up(): void
    {
        foreach ($this->settings as $row) {
            if (!DB::table('public_settings')->where('key', $row['key'])->exists()) {
                DB::table('public_settings')->insert($row + ['type' => 'string', 'created_at' => now(), 'updated_at' => now()]);
            }
        }
    }

    public function down(): void
    {
        DB::table('public_settings')->whereIn('key', array_column($this->settings, 'key'))->delete();
    }
};
