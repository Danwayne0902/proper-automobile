<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Automobile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DebugBookingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function debug_booking_creation()
    {
        // Create a customer and dealer
        $customer = User::factory()->create(['role' => 'customer']);
        $dealer = User::factory()->create(['role' => 'dealer']);

        // Create an available automobile
        $automobile = Automobile::factory()->create([
            'dealer_id' => $dealer->id,
            'status' => 'available'
        ]);

        // Try to create a booking
        $response = $this->actingAs($customer)
                         ->post(route('bookings.store'), [
                             'automobile_id' => $automobile->id,
                             'type' => 'test_drive',
                             'scheduled_at' => now()->addDay()->format('Y-m-d H:i:s'),
                             'preferred_time' => 'Morning (9:00 AM - 12:00 PM)'
                         ]);

        // Output the response for debugging
        echo "Response status: " . $response->status() . "\n";
        echo "Response content: " . $response->getContent() . "\n";

        // Check if any bookings were created
        $this->assertDatabaseCount('bookings', 1);
    }
}
