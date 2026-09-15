<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\City;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $city = City::where('name', 'Patiala')->first();

        if (!$city) {
            return;
        }

        $areas = [
            ['name' => 'Model Town', 'pincode' => '147001'],
            ['name' => 'Urban Estate', 'pincode' => '147002'],
            ['name' => 'Rajpura Road', 'pincode' => '147003'],
            ['name' => 'Leela Bhawan', 'pincode' => '147001'],
            ['name' => 'Tripuri', 'pincode' => '147001'],
            ['name' => 'Nabha Road', 'pincode' => '147004'],
            ['name' => 'Sanaur Road', 'pincode' => '147001'],
            ['name' => 'Patiala Bypass', 'pincode' => '147005'],
            ['name' => 'Bhupindra Road', 'pincode' => '147001'],
            ['name' => 'Lower Mall', 'pincode' => '147001'],
            ['name' => 'Sirhind Road', 'pincode' => '147001'],
        ];

        foreach ($areas as $area) {
            Area::updateOrCreate(
                ['city_id' => $city->id, 'name' => $area['name']],
                ['pincode' => $area['pincode'], 'is_active' => true]
            );
        }
    }
}
