<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'company_name' => 'ABC Holding BV',
                'contact_name' => 'Jan de Vries',
                'email' => 'jan@abcholding.nl',
                'phone' => '+31 20 555 1234',
                'address' => 'Keizersgracht 123, 1015 CW Amsterdam',
                'kvk_number' => '12345678',
                'btw_number' => 'NL123456789B01',
            ],
            [
                'company_name' => 'XYZ Properties',
                'contact_name' => 'Anna Bakker',
                'email' => 'anna@xyzproperties.nl',
                'phone' => '+31 30 555 5678',
                'address' => 'Oudegracht 45, 3511 AM Utrecht',
                'kvk_number' => '87654321',
                'btw_number' => 'NL987654321B01',
            ],
            [
                'company_name' => 'GreenScape Garden Services',
                'contact_name' => 'Pieter Jansen',
                'email' => 'info@greenscape.nl',
                'phone' => '+31 40 555 9876',
                'address' => 'Vestdijk 22, 5611 CC Eindhoven',
                'kvk_number' => '55667788',
                'btw_number' => 'NL556677889B01',
            ],
            [
                'company_name' => 'De Mooie Tuin BV',
                'contact_name' => 'Sanne van der Berg',
                'email' => 'sanne@demooietuin.nl',
                'phone' => '+31 10 555 3456',
                'address' => 'Westersingel 12, 3014 GN Rotterdam',
                'kvk_number' => '11223344',
                'btw_number' => 'NL112233449B01',
            ],
        ];

        $count = 0;
        foreach ($clients as $client) {
            // Check if client already exists
            if (!Client::where('company_name', $client['company_name'])->exists()) {
                Client::create($client);
                $count++;
            }
        }
        
        $this->command->info("Created {$count} new clients. " . (count($clients) - $count) . " clients already existed.");
    }
}
