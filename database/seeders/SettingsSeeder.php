<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'company_name' => 'YOHANNES HOVENIERSBEDRIJF B.V.',
            'company_address' => 'Aristotelesstraat 993',
            'company_postal_city' => '7323 NZ Apeldoorn',
            'company_phone' => '0616638510',
            'company_email' => 'info@yohanneshoveniersbedrijf.com',
            'company_website' => 'www.yohanneshoveniersbedrijf.com',
            'company_kvk' => '92625703',
            'company_btw' => 'NL866120877B01',
            'bank_name' => 'ABN AMRO',
            'bank_iban' => 'NL44ABNA0108854914',
            'default_payment_days' => '14',
            'default_btw_percentage' => '21',
            'logo_path' => 'logo.png',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key_name' => $key],
                ['value' => $value]
            );
        }

        $this->command->info('Default settings have been added.');
    }
}
