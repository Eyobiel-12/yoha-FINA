<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Company Information
            'company_name' => 'Yohannes Hoveniersbedrijf B.V.',
            'company_address' => 'Voorbeeldstraat 123, 1234 AB Amsterdam',
            'company_phone' => '+31 20 123 4567',
            'company_email' => 'info@yohanneshoveniers.nl',
            'company_website' => 'www.yohanneshoveniers.nl',
            'company_kvk' => 'KVK: 12345678',
            'company_btw' => 'BTW: NL123456789B01',
            
            // Bank Information
            'bank_name' => 'ING Bank',
            'bank_iban' => 'NL91 INGB 0123 4567 89',
            
            // Invoice Settings
            'default_payment_days' => '14',
            'default_btw_percentage' => '21',
            
            // Logo
            'logo_path' => 'images/logo.png',
        ];
        
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key_name' => $key],
                ['value' => $value]
            );
        }
    }
}
