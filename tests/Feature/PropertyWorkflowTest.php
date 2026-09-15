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

class PropertyWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $owner;
    protected $property;
    protected $area;
    protected $category;
    protected $type;

    protected function setUp(): void
    {
        parent::setUp();

        $city = City::create(['name' => 'Patiala', 'state' => 'Punjab', 'country' => 'India']);
        $this->area = Area::create(['city_id' => $city->id, 'name' => 'Model Town', 'pincode' => '147001']);
        
        $this->category = PropertyCategory::create(['name' => 'Residential', 'slug' => 'residential']);
        $this->type = PropertyType::create(['category_id' => $this->category->id, 'name' => 'Flat', 'slug' => 'flat']);

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'mobile' => '9999999999',
            'password' => bcrypt('password123'),
            'role' => 'admin'
        ]);

        $this->owner = User::create([
            'name' => 'Owner User',
            'email' => 'owner@test.com',
            'mobile' => '8888888888',
            'password' => bcrypt('password123'),
            'role' => 'owner'
        ]);

        $this->property = Property::create([
            'user_id' => $this->owner->id,
            'category_id' => $this->category->id,
            'property_type_id' => $this->type->id,
            'area_id' => $this->area->id,
            'title' => 'Awaiting Flat',
            'slug' => 'awaiting-flat',
            'description' => 'Awaiting approval Flat',
            'purpose' => 'rent',
            'status' => 'pending_review',
            'price' => 12000,
            'price_type' => 'fixed',
            'property_area' => 1200,
            'area_unit' => 'sq_ft',
            'parking' => 'none',
            'furnishing' => 'unfurnished',
            'address' => 'Model Town lane 2'
        ]);
    }

    public function test_admin_can_approve_property()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.properties.approve', $this->property->id), [
            'action' => 'approve',
            'admin_notes' => 'Looking good, approved.'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('properties', [
            'id' => $this->property->id,
            'status' => 'published',
            'admin_notes' => 'Looking good, approved.'
        ]);
    }

    public function test_admin_can_request_changes_on_property()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.properties.approve', $this->property->id), [
            'action' => 'request_changes',
            'admin_notes' => 'Please upload better pictures.'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('properties', [
            'id' => $this->property->id,
            'status' => 'changes_requested',
            'admin_notes' => 'Please upload better pictures.'
        ]);
    }

    public function test_owner_cannot_approve_property()
    {
        $response = $this->actingAs($this->owner)->post(route('admin.properties.approve', $this->property->id), [
            'action' => 'approve',
            'admin_notes' => 'Self-approving.'
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_add_locality_area()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.areas.store'), [
            'name' => 'Leela Bhawan',
            'pincode' => '147001'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('areas', [
            'name' => 'Leela Bhawan',
            'pincode' => '147001'
        ]);
    }

    public function test_admin_can_toggle_locality_status()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.areas.toggle', $this->area->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('areas', [
            'id' => $this->area->id,
            'is_active' => false
        ]);
    }
}
