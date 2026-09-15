<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\City;
use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\PropertyType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyTest extends TestCase
{
    use RefreshDatabase;

    protected $owner;
    protected $agent;
    protected $user;
    protected $area;
    protected $category;
    protected $type;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Seed Data
        $city = City::create(['name' => 'Patiala', 'state' => 'Punjab', 'country' => 'India']);
        $this->area = Area::create(['city_id' => $city->id, 'name' => 'Model Town', 'pincode' => '147001']);
        
        $this->category = PropertyCategory::create(['name' => 'Residential', 'slug' => 'residential']);
        $this->type = PropertyType::create(['category_id' => $this->category->id, 'name' => 'Flat', 'slug' => 'flat']);

        // Create Users
        $this->owner = User::create([
            'name' => 'Test Owner',
            'email' => 'owner@test.com',
            'mobile' => '9876543210',
            'password' => bcrypt('password123'),
            'role' => 'owner'
        ]);

        $this->agent = User::create([
            'name' => 'Test Agent',
            'email' => 'agent@test.com',
            'mobile' => '9876543211',
            'password' => bcrypt('password123'),
            'role' => 'agent'
        ]);

        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'user@test.com',
            'mobile' => '9876543212',
            'password' => bcrypt('password123'),
            'role' => 'user'
        ]);
    }

    public function test_can_view_properties_index()
    {
        Property::create([
            'user_id' => $this->owner->id,
            'category_id' => $this->category->id,
            'property_type_id' => $this->type->id,
            'area_id' => $this->area->id,
            'title' => 'Luxury Flat',
            'slug' => 'luxury-flat',
            'description' => 'Great flat description',
            'purpose' => 'sell',
            'status' => 'published',
            'price' => 5000000,
            'price_type' => 'fixed',
            'property_area' => 1500,
            'area_unit' => 'sq_ft',
            'parking' => 'none',
            'furnishing' => 'unfurnished',
            'address' => 'Model Town main road'
        ]);

        $response = $this->get(route('properties.index'));

        $response->assertStatus(200);
        $response->assertSee('Luxury Flat');
    }

    public function test_cannot_see_unpublished_properties_on_index()
    {
        Property::create([
            'user_id' => $this->owner->id,
            'category_id' => $this->category->id,
            'property_type_id' => $this->type->id,
            'area_id' => $this->area->id,
            'title' => 'Draft Flat',
            'slug' => 'draft-flat',
            'description' => 'Great draft flat',
            'purpose' => 'sell',
            'status' => 'pending_review',
            'price' => 5000000,
            'price_type' => 'fixed',
            'property_area' => 1500,
            'area_unit' => 'sq_ft',
            'parking' => 'none',
            'furnishing' => 'unfurnished',
            'address' => 'Model Town main road'
        ]);

        $response = $this->get(route('properties.index'));

        $response->assertStatus(200);
        $response->assertDontSee('Draft Flat');
    }

    public function test_owner_can_create_property_listing()
    {
        $response = $this->actingAs($this->owner)->post(route('properties.store'), [
            'title' => 'My New House Listing',
            'description' => 'This is a beautiful new house listing description.',
            'purpose' => 'sell',
            'category_id' => $this->category->id,
            'property_type_id' => $this->type->id,
            'area_id' => $this->area->id,
            'price' => 4500000,
            'price_type' => 'negotiable',
            'property_area' => 1200,
            'area_unit' => 'sq_ft',
            'parking' => 'car',
            'furnishing' => 'unfurnished',
            'address' => 'Green Fields enclave',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('properties', [
            'title' => 'My New House Listing',
            'status' => 'pending_review' // Listings go to pending by default
        ]);
    }

    public function test_normal_user_cannot_create_property_listing()
    {
        $response = $this->actingAs($this->user)->post(route('properties.store'), [
            'title' => 'User Listing',
            'description' => 'This should fail.',
            'purpose' => 'sell',
            'category_id' => $this->category->id,
            'property_type_id' => $this->type->id,
            'area_id' => $this->area->id,
            'price' => 4500000,
            'price_type' => 'negotiable',
            'property_area' => 1200,
            'area_unit' => 'sq_ft',
            'parking' => 'car',
            'furnishing' => 'unfurnished',
            'address' => 'Green Fields enclave',
        ]);

        $response->assertStatus(403);
    }
}
