<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Option A) Inline dataset (extend this array to all countries).
        $countries = [

            ['name' => 'Egypt','code' => 'EG', 'phone_code' => '20',   'currency_code' => 'EGP', 'currency_sympol' => 'E£'],
        
        ];

        // Option B) Load from JSON file (paste full list into database/seeders/data/countries.json)
        // $countries = json_decode(file_get_contents(database_path('seeders/data/countries.json')), true);

        // Normalize & add timestamps
        $countries = array_map(function ($c) use ($now) {
            return [
                'name'            => $c['name'],
                'code'            => strtoupper($c['code']),
                'phone_code'      => (string) $c['phone_code'],
                'currency_code'   => $c['currency_code'] ?? null,
                'currency_sympol' => $c['currency_sympol'] ?? null,
                'created_at'      => $now,
                'updated_at'      => $now,
            ];
        }, $countries);

        // Upsert by ISO2 code to avoid duplicates on re-seed
        DB::table('countries')->upsert(
            $countries,
            ['code'],
            ['name', 'phone_code', 'currency_code', 'currency_sympol', 'updated_at']
        );
    }
}
