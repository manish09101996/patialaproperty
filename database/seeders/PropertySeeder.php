<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Amenity;
use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\PropertyType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('role', 'owner')->first();
        $agent = User::where('role', 'agent')->first();
        
        $modelTown = Area::where('name', 'Model Town')->first();
        $urbanEstate = Area::where('name', 'Urban Estate')->first();
        $leelaBhawan = Area::where('name', 'Leela Bhawan')->first();
        $nabhaRoad = Area::where('name', 'Nabha Road')->first();
        $tripuri = Area::where('name', 'Tripuri')->first();

        $resCat = PropertyCategory::where('name', 'Residential')->first();
        $comCat = PropertyCategory::where('name', 'Commercial')->first();

        $flatType = PropertyType::where('name', 'Flat')->first();
        $houseType = PropertyType::where('name', 'Independent House')->first();
        $officeType = PropertyType::where('name', 'Office')->first();
        $plotType = PropertyType::where('name', 'Residential Plot')->first();
        $shopType = PropertyType::where('name', 'Shop')->first();

        $amenities = Amenity::all();

        // 1. Luxury 3 BHK Flat in Model Town (Rent)
        $p1 = Property::updateOrCreate(
            ['slug' => 'luxury-3-bhk-flat-in-model-town-patiala'],
            [
                'user_id' => $owner->id,
                'category_id' => $resCat->id,
                'property_type_id' => $flatType->id,
                'area_id' => $modelTown->id,
                'title' => 'Luxury 3 BHK Flat in Model Town, Patiala',
                'description' => 'A beautiful, fully-furnished 3 BHK flat situated in the heart of Model Town, Patiala. Close to schools, local markets, and public parks. Includes modern bathrooms, modular kitchen, and private balconies.',
                'purpose' => 'rent',
                'status' => 'published',
                'price' => 15000.00,
                'price_type' => 'fixed',
                'property_area' => 1800.00,
                'area_unit' => 'sq_ft',
                'bedrooms' => 3,
                'bathrooms' => 3,
                'balconies' => 2,
                'floor_number' => 2,
                'total_floors' => 4,
                'parking' => 'both',
                'facing' => 'East',
                'furnishing' => 'fully-furnished',
                'property_age' => 2,
                'address' => 'House No. 124, Gali No. 3, near Model Town Club, Model Town, Patiala',
                'pincode' => '147001',
                'latitude' => 30.3235,
                'longitude' => 76.3854,
                'google_map_location' => 'https://maps.google.com/?q=30.3235,76.3854',
                'is_featured' => true,
            ]
        );
        $p1->amenities()->sync($amenities->pluck('id')->take(6));
        $p1->media()->updateOrCreate(
            ['file_path' => 'demo/model_town_flat_primary.jpg'],
            ['file_type' => 'image', 'is_primary' => true, 'sort_order' => 0]
        );
        $p1->media()->updateOrCreate(
            ['file_path' => 'demo/model_town_flat_bedroom.jpg'],
            ['file_type' => 'image', 'is_primary' => false, 'sort_order' => 1]
        );

        // 2. Independent 4 BHK House in Urban Estate (Sell)
        $p2 = Property::updateOrCreate(
            ['slug' => 'independent-4-bhk-house-in-urban-estate-patiala'],
            [
                'user_id' => $agent->id,
                'category_id' => $resCat->id,
                'property_type_id' => $houseType->id,
                'area_id' => $urbanEstate->id,
                'title' => 'Independent 4 BHK House in Urban Estate, Patiala',
                'description' => 'Newly constructed double-story independent house in Urban Estate Phase II. Features premium wood works, spacious marble-finished rooms, a garden yard, and wide parking space. Situated in a highly peaceful neighborhood.',
                'purpose' => 'sell',
                'status' => 'published',
                'price' => 8500000.00,
                'price_type' => 'negotiable',
                'property_area' => 2500.00,
                'area_unit' => 'sq_ft',
                'bedrooms' => 4,
                'bathrooms' => 4,
                'balconies' => 3,
                'floor_number' => 0,
                'total_floors' => 2,
                'parking' => 'both',
                'facing' => 'North-East',
                'furnishing' => 'semi-furnished',
                'property_age' => 0,
                'address' => 'Plot 432-B, Sector 2, Urban Estate, Patiala',
                'pincode' => '147002',
                'latitude' => 30.3421,
                'longitude' => 76.4385,
                'google_map_location' => 'https://maps.google.com/?q=30.3421,76.4385',
                'is_featured' => true,
            ]
        );
        $p2->amenities()->sync($amenities->pluck('id')->filter(fn($id) => in_array($id, [3, 4, 6, 8, 9])));
        $p2->media()->updateOrCreate(
            ['file_path' => 'demo/urban_estate_house_primary.jpg'],
            ['file_type' => 'image', 'is_primary' => true, 'sort_order' => 0]
        );

        // 3. Prime Commercial Office Space in Leela Bhawan (Rent)
        $p3 = Property::updateOrCreate(
            ['slug' => 'prime-commercial-office-space-in-leela-bhawan-patiala'],
            [
                'user_id' => $agent->id,
                'category_id' => $comCat->id,
                'property_type_id' => $officeType->id,
                'area_id' => $leelaBhawan->id,
                'title' => 'Prime Commercial Office Space in Leela Bhawan, Patiala',
                'description' => 'Ready to move commercial office space located on the main road of Leela Bhawan Market. Excellent visibility, perfect for banks, corporate offices, IT services, or diagnostics centers. Dedicated lobby and washrooms.',
                'purpose' => 'rent',
                'status' => 'published',
                'price' => 45000.00,
                'price_type' => 'fixed',
                'property_area' => 1500.00,
                'area_unit' => 'sq_ft',
                'floor_number' => 1,
                'total_floors' => 3,
                'parking' => 'car',
                'facing' => 'West',
                'furnishing' => 'unfurnished',
                'property_age' => 5,
                'address' => 'SCO 14, First Floor, Leela Bhawan Commercial Complex, Patiala',
                'pincode' => '147001',
                'latitude' => 30.3340,
                'longitude' => 76.3812,
                'google_map_location' => 'https://maps.google.com/?q=30.3340,76.3812',
                'is_featured' => false,
            ]
        );
        $p3->amenities()->sync([3, 4, 5, 7]);
        $p3->media()->updateOrCreate(
            ['file_path' => 'demo/leela_bhawan_office_primary.jpg'],
            ['file_type' => 'image', 'is_primary' => true, 'sort_order' => 0]
        );

        // 4. Residential Plot near Nabha Road (Sell)
        $p4 = Property::updateOrCreate(
            ['slug' => 'residential-plot-near-nabha-road-patiala'],
            [
                'user_id' => $owner->id,
                'category_id' => $resCat->id,
                'property_type_id' => $plotType->id,
                'area_id' => $nabhaRoad->id,
                'title' => 'Residential Plot near Nabha Road, Patiala',
                'description' => 'A spacious 10 Marla (approx. 2250 sq.ft) residential plot inside a gated colony on Nabha Road, Patiala. Front facing wide metal road, immediate registry and possession, water and sewer lines are fully laid.',
                'purpose' => 'sell',
                'status' => 'published',
                'price' => 2500000.00,
                'price_type' => 'negotiable',
                'property_area' => 10.00,
                'area_unit' => 'marla',
                'parking' => 'none',
                'facing' => 'South',
                'furnishing' => 'unfurnished',
                'property_age' => 0,
                'address' => 'Plot No 84, Green Enclave, near Nabha Road Bypass, Patiala',
                'pincode' => '147004',
                'latitude' => 30.3540,
                'longitude' => 76.3412,
                'google_map_location' => 'https://maps.google.com/?q=30.3540,76.3412',
                'is_featured' => false,
            ]
        );
        $p4->amenities()->sync([4, 8]);
        $p4->media()->updateOrCreate(
            ['file_path' => 'demo/nabha_road_plot_primary.jpg'],
            ['file_type' => 'image', 'is_primary' => true, 'sort_order' => 0]
        );

        // 5. Pending Review Shop in Tripuri (Sell)
        $p5 = Property::updateOrCreate(
            ['slug' => 'pending-review-shop-in-tripuri-patiala'],
            [
                'user_id' => $owner->id,
                'category_id' => $comCat->id,
                'property_type_id' => $shopType->id,
                'area_id' => $tripuri->id,
                'title' => 'Pending Review Commercial Shop in Tripuri Market, Patiala',
                'description' => 'Perfect retail outlet space in Tripuri market. Highly crowded local shopping zone. Good footfall, ideal for garments, electrical shop, or salon. Currently waiting for admin review.',
                'purpose' => 'sell',
                'status' => 'pending_review',
                'price' => 3500000.00,
                'price_type' => 'negotiable',
                'property_area' => 350.00,
                'area_unit' => 'sq_ft',
                'floor_number' => 0,
                'total_floors' => 1,
                'parking' => 'bike',
                'facing' => 'North',
                'furnishing' => 'unfurnished',
                'property_age' => 3,
                'address' => 'Shop No. 72, Main Bazar, Tripuri, Patiala',
                'pincode' => '147001',
                'latitude' => 30.3551,
                'longitude' => 76.3982,
                'google_map_location' => 'https://maps.google.com/?q=30.3551,76.3982',
                'is_featured' => false,
            ]
        );
        $p5->amenities()->sync([8]);
        $p5->media()->updateOrCreate(
            ['file_path' => 'demo/tripuri_shop_primary.jpg'],
            ['file_type' => 'image', 'is_primary' => true, 'sort_order' => 0]
        );
    }
}
