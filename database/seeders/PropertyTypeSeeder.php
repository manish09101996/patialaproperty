<?php

namespace Database\Seeders;

use App\Models\PropertyCategory;
use App\Models\PropertyType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PropertyTypeSeeder extends Seeder
{
    public function run(): void
    {
        $res = PropertyCategory::where('name', 'Residential')->first();
        $com = PropertyCategory::where('name', 'Commercial')->first();

        if (!$res || !$com) {
            return;
        }

        $residentialTypes = [
            'Apartment', 'Flat', 'Villa', 'Independent House',
            'Builder Floor', 'Residential Plot', 'Farm House', 'Studio Apartment'
        ];

        $commercialTypes = [
            'Office', 'Shop', 'Showroom', 'Warehouse',
            'Industrial Shed', 'Commercial Plot', 'Co-working Space', 'Factory'
        ];

        foreach ($residentialTypes as $type) {
            PropertyType::updateOrCreate(
                ['category_id' => $res->id, 'slug' => Str::slug($type)],
                ['name' => $type, 'is_active' => true]
            );
        }

        foreach ($commercialTypes as $type) {
            PropertyType::updateOrCreate(
                ['category_id' => $com->id, 'slug' => Str::slug($type)],
                ['name' => $type, 'is_active' => true]
            );
        }
    }
}
