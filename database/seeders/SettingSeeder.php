<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'institute_name', 'value' => 'Apex Premier Academy for JEE & NEET', 'group' => 'institute'],
            ['key' => 'institute_code', 'value' => 'APEX-EDU', 'group' => 'institute'],
            ['key' => 'contact_email', 'value' => 'contact@apexcoaching.edu', 'group' => 'institute'],
            ['key' => 'contact_phone', 'value' => '+91 98765 43210', 'group' => 'institute'],
            ['key' => 'address', 'value' => 'Plot 42, Education Hub, Knowledge Park III, Greater Noida, UP - 201310', 'group' => 'institute'],
            ['key' => 'gst_number', 'value' => '07AAAAA0000A1Z5', 'group' => 'finance'],
            ['key' => 'currency_symbol', 'value' => '₹', 'group' => 'finance'],
            ['key' => 'receipt_prefix', 'value' => 'APEX/REC/2026/', 'group' => 'finance'],
            ['key' => 'academic_year_start', 'value' => '2026-04-01', 'group' => 'academic'],
            ['key' => 'academic_year_end', 'value' => '2027-03-31', 'group' => 'academic'],
            ['key' => 'late_fee_per_day', 'value' => '50.00', 'group' => 'finance'],
            ['key' => 'grace_days_allowed', 'value' => '5', 'group' => 'finance'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
