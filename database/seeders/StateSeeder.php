<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('states')->insert([
            ['country_id' => 1, 'name' => 'Cairo'],
            ['country_id' => 1, 'name' => 'Alexandria'],
            ['country_id' => 1, 'name' => 'Giza'],
            ['country_id' => 1, 'name' => 'Qalyubia'],
            ['country_id' => 1, 'name' => 'Port Said'],
            ['country_id' => 1, 'name' => 'Suez'],
            ['country_id' => 1, 'name' => 'Dakahlia'],
            ['country_id' => 1, 'name' => 'Sharqia'],
            ['country_id' => 1, 'name' => 'Kafr El Sheikh'],
            ['country_id' => 1, 'name' => 'Gharbia'],
            ['country_id' => 1, 'name' => 'Monufia'],
            ['country_id' => 1, 'name' => 'Beheira'],
            ['country_id' => 1, 'name' => 'Ismailia'],
            ['country_id' => 1, 'name' => 'Giza'],
            ['country_id' => 1, 'name' => 'Beni Suef'],
            ['country_id' => 1, 'name' => 'Faiyum'],
            ['country_id' => 1, 'name' => 'Minya'],
            ['country_id' => 1, 'name' => 'Asyut'],
            ['country_id' => 1, 'name' => 'Sohag'],
            ['country_id' => 1, 'name' => 'Qena'],
            ['country_id' => 1, 'name' => 'Luxor'],
            ['country_id' => 1, 'name' => 'Aswan'],
            ['country_id' => 1, 'name' => 'Red Sea'],
            ['country_id' => 1, 'name' => 'New Valley'],
            ['country_id' => 1, 'name' => 'Matrouh'],
            ['country_id' => 1, 'name' => 'North Sinai'],
            ['country_id' => 1, 'name' => 'South Sinai'],
        ]);
    }
}
