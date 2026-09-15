<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        $amenities = [
            ['name' => 'Swimming Pool', 'icon' => 'swimming-pool'],
            ['name' => 'Gym', 'icon' => 'gym'],
            ['name' => 'Power Backup', 'icon' => 'power-backup'],
            ['name' => '24/7 Security', 'icon' => 'security'],
            ['name' => 'Lift / Elevator', 'icon' => 'lift'],
            ['name' => 'Reserved Parking', 'icon' => 'parking'],
            ['name' => 'Wi-Fi / Internet', 'icon' => 'wifi'],
            ['name' => 'Water Supply', 'icon' => 'water'],
            ['name' => 'Park / Garden', 'icon' => 'garden'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::updateOrCreate(
                ['name' => $amenity['name']],
                ['icon' => $amenity['icon']]
            );
        }
    }
}
