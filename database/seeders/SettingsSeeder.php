<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['key' => 'store_name',       'value' => 'Plant House',           'group' => 'general'],
            ['key' => 'store_email',      'value' => 'info@planthouse.qa',    'group' => 'general'],
            ['key' => 'store_phone',      'value' => '+974 5555 1234',        'group' => 'general'],
            ['key' => 'store_address',    'value' => 'الدوحة، قطر',           'group' => 'general'],
            ['key' => 'currency',         'value' => 'QAR',                   'group' => 'general'],
            ['key' => 'shipping_fee',     'value' => '15',                    'group' => 'shipping'],
            ['key' => 'free_shipping_at', 'value' => '200',                   'group' => 'shipping'],
            ['key' => 'social_instagram', 'value' => '',                      'group' => 'social'],
            ['key' => 'social_twitter',   'value' => '',                      'group' => 'social'],
            ['key' => 'social_whatsapp',  'value' => '+974 5555 1234',        'group' => 'social'],
        ];

        foreach ($defaults as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
