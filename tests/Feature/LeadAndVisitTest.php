<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\City;
use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\PropertyType;
use App\Models\PropertyLead;
use App\Models\PropertyVisit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadAndVisitTest extends TestCase
{
    use RefreshDatabase;

    protected $owner;
    protected $buyer;
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

        $this->owner = User::create([
            'name' => 'Property Owner',
            'email' => 'owner@test.com',
            'mobile' => '9876543210',
            'password' => bcrypt('password123'),
            'role' => 'owner'
        ]);

        $this->buyer = User::create([
            'name' => 'Buyer User',
            'email' => 'buyer@test.com',
            'mobile' => '9876543211',
            'password' => bcrypt('password123'),
            'role' => 'user'
        ]);

        $this->property = Property::create([
            'user_id' => $this->owner->id,
            'category_id' => $this->category->id,
            'property_type_id' => $this->type->id,
            'area_id' => $this->area->id,
            'title' => 'Nice Flat',
            'slug' => 'nice-flat',
            'description' => 'A nice flat in Patiala',
            'purpose' => 'sell',
            'status' => 'published',
            'price' => 4000000,
            'price_type' => 'fixed',
            'property_area' => 1200,
            'area_unit' => 'sq_ft',
            'parking' => 'none',
            'furnishing' => 'unfurnished',
            'address' => 'Model Town main road'
        ]);
    }

    public function test_buyer_can_send_lead_inquiry()
    {
        $response = $this->actingAs($this->buyer)->post(route('leads.store', $this->property->id), [
            'source' => 'contact_owner',
            'contact_type' => 'in_app',
            'message' => 'I would love to view this flat.'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('property_leads', [
            'property_id' => $this->property->id,
            'owner_id' => $this->owner->id,
            'user_id' => $this->buyer->id,
            'source' => 'contact_owner',
            'message' => 'I would love to view this flat.'
        ]);
    }

    public function test_owner_cannot_inquire_on_own_property()
    {
        $response = $this->actingAs($this->owner)->post(route('leads.store', $this->property->id), [
            'source' => 'contact_owner',
            'contact_type' => 'in_app',
            'message' => 'Illegal inquiry'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('property_leads', [
            'message' => 'Illegal inquiry'
        ]);
    }

    public function test_owner_can_update_lead_status()
    {
        $lead = PropertyLead::create([
            'property_id' => $this->property->id,
            'owner_id' => $this->owner->id,
            'user_id' => $this->buyer->id,
            'source' => 'contact_owner',
            'contact_type' => 'in_app',
            'status' => 'new'
        ]);

        $response = $this->actingAs($this->owner)->post(route('leads.status', $lead->id), [
            'status' => 'contacted'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('property_leads', [
            'id' => $lead->id,
            'status' => 'contacted'
        ]);
    }

    public function test_buyer_can_schedule_site_visit()
    {
        $response = $this->actingAs($this->buyer)->post(route('visits.store', $this->property->id), [
            'visit_date' => date('Y-m-d', strtotime('+2 days')),
            'time_slot' => '10:00 AM - 12:00 PM',
            'user_notes' => 'Looking forward to meeting you.'
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('property_leads', [
            'property_id' => $this->property->id,
            'user_id' => $this->buyer->id,
            'source' => 'schedule_visit'
        ]);

        $this->assertDatabaseHas('property_visits', [
            'property_id' => $this->property->id,
            'user_id' => $this->buyer->id,
            'status' => 'pending',
            'time_slot' => '10:00 AM - 12:00 PM'
        ]);
    }

    public function test_owner_can_approve_site_visit()
    {
        $lead = PropertyLead::create([
            'property_id' => $this->property->id,
            'owner_id' => $this->owner->id,
            'user_id' => $this->buyer->id,
            'source' => 'schedule_visit',
            'contact_type' => 'in_app'
        ]);

        $visit = PropertyVisit::create([
            'property_id' => $this->property->id,
            'lead_id' => $lead->id,
            'user_id' => $this->buyer->id,
            'visit_date' => date('Y-m-d', strtotime('+2 days')),
            'time_slot' => '10:00 AM - 12:00 PM',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->owner)->post(route('visits.status', $visit->id), [
            'status' => 'confirmed',
            'owner_notes' => 'Confirmed, see you there!'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('property_visits', [
            'id' => $visit->id,
            'status' => 'confirmed',
            'owner_notes' => 'Confirmed, see you there!'
        ]);
    }
}
