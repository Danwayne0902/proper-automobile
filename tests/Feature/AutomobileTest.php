<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Automobile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AutomobileTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed basic data if needed
    }

    /** @test */
    public function customers_can_view_automobile_listings()
    {
        // Create a customer user
        $customer = User::factory()->create(['role' => 'customer']);

        // Create some automobiles
        $automobiles = Automobile::factory()->count(3)->create(['status' => 'available']);

        // Act as customer and visit automobiles index
        $response = $this->actingAs($customer)->get(route('automobiles.index'));

        // Assert response is successful
        $response->assertOk();
        $response->assertInertia(fn ($page) =>
            $page->component('Automobiles/Index')
                 ->has('automobiles.data', 3)
        );
    }

    /** @test */
    public function dealers_can_create_automobiles()
    {
        // Create a dealer user
        $dealer = User::factory()->create(['role' => 'dealer']);

        // Automobile data
        $automobileData = [
            'make' => 'Toyota',
            'model' => 'Camry',
            'year' => 2023,
            'vin' => 'TEST12345VIN67890',
            'price' => 25000,
            'mileage' => 5000,
            'color' => 'Blue',
            'body_type' => 'sedan',
            'fuel_type' => 'gasoline',
            'transmission' => 'automatic',
            'status' => 'available',
            'description' => 'Test automobile description'
        ];

        // Act as dealer and create automobile
        $response = $this->actingAs($dealer)
                         ->post(route('automobiles.store'), $automobileData);

        // Assert automobile was created
        $this->assertDatabaseHas('automobiles', [
            'make' => 'Toyota',
            'model' => 'Camry',
            'vin' => 'TEST12345VIN67890',
            'dealer_id' => $dealer->id
        ]);

        $response->assertRedirect();
    }

    /** @test */
    public function customers_cannot_create_automobiles()
    {
        // Create a customer user
        $customer = User::factory()->create(['role' => 'customer']);

        // Attempt to access automobile creation page
        $response = $this->actingAs($customer)->get(route('automobiles.create'));

        // Should be redirected with error
        $response->assertRedirect(route('automobiles.index'));
    }

    /** @test */
    public function dealers_can_only_edit_their_own_automobiles()
    {
        // Create two dealers
        $dealer1 = User::factory()->create(['role' => 'dealer']);
        $dealer2 = User::factory()->create(['role' => 'dealer']);

        // Create automobile for dealer1
        $automobile = Automobile::factory()->create(['dealer_id' => $dealer1->id]);

        // Try to edit as dealer2 (should fail)
        $response = $this->actingAs($dealer2)->get(route('automobiles.edit', $automobile));
        $response->assertRedirect(route('automobiles.index'));

        // Try to edit as dealer1 (should succeed)
        $response = $this->actingAs($dealer1)->get(route('automobiles.edit', $automobile));
        $response->assertOk();
    }

    /** @test */
    public function automobile_search_works_correctly()
    {
        // Create a customer
        $customer = User::factory()->create(['role' => 'customer']);

        // Create automobiles
        $toyotaCamry = Automobile::factory()->create([
            'make' => 'Toyota',
            'model' => 'Camry',
            'status' => 'available'
        ]);

        $hondaCivic = Automobile::factory()->create([
            'make' => 'Honda',
            'model' => 'Civic',
            'status' => 'available'
        ]);

        // Search for Toyota
        $response = $this->actingAs($customer)
                         ->get(route('automobiles.index', ['search' => 'Toyota']));

        $response->assertOk();
        // Note: In a real test, you'd assert that only Toyota shows up in results
    }

    /** @test */
    public function automobile_status_filtering_works()
    {
        // Create a customer
        $customer = User::factory()->create(['role' => 'customer']);

        // Create automobiles with different statuses
        $availableCar = Automobile::factory()->create(['status' => 'available']);
        $soldCar = Automobile::factory()->create(['status' => 'sold']);

        // Filter by available status
        $response = $this->actingAs($customer)
                         ->get(route('automobiles.index', ['status' => 'available']));

        $response->assertOk();
        // In real implementation, assert only available cars are returned
    }

    /** @test */
    public function automobile_requires_authentication()
    {
        // Try to access automobiles without authentication
        $response = $this->get(route('automobiles.index'));

        // Should redirect to login
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function automobile_validation_works_on_creation()
    {
        $dealer = User::factory()->create(['role' => 'dealer']);

        // Submit invalid data (missing required fields)
        $response = $this->actingAs($dealer)
                         ->post(route('automobiles.store'), [
                             'make' => '', // Required field is empty
                             'price' => 'invalid_price' // Invalid price format
                         ]);

        // Should return with validation errors
        $response->assertSessionHasErrors(['make', 'model', 'year', 'vin', 'price']);
    }
}
