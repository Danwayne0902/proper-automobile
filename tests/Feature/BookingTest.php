<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Automobile;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;

class BookingTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function customers_can_create_bookings()
    {
        // Create users
        $customer = User::factory()->create(['role' => 'customer']);
        $dealer = User::factory()->create(['role' => 'dealer']);

        // Create automobile
        $automobile = Automobile::factory()->create([
            'dealer_id' => $dealer->id,
            'status' => 'available'
        ]);

        // Booking data
        $bookingData = [
            'automobile_id' => $automobile->id,
            'type' => 'test_drive',
            'scheduled_at' => Carbon::tomorrow()->toDateTimeString(),
            'preferred_time' => 'Morning (9:00 AM - 12:00 PM)',
            'notes' => 'Test booking notes'
        ];

        // Create booking as customer
        $response = $this->actingAs($customer)
                         ->post(route('bookings.store'), $bookingData);

        // Assert booking was created
        $this->assertDatabaseHas('bookings', [
            'user_id' => $customer->id,
            'automobile_id' => $automobile->id,
            'type' => 'test_drive',
            'status' => 'pending'
        ]);

        $response->assertRedirect();
    }

    /** @test */
    public function dealers_cannot_create_bookings()
    {
        $dealer = User::factory()->create(['role' => 'dealer']);

        // Try to access booking creation
        $response = $this->actingAs($dealer)->get(route('bookings.create'));

        $response->assertRedirect(route('bookings.index'));
    }

    /** @test */
    public function customers_can_only_edit_their_own_pending_bookings()
    {
        // Create users
        $customer1 = User::factory()->create(['role' => 'customer']);
        $customer2 = User::factory()->create(['role' => 'customer']);
        $dealer = User::factory()->create(['role' => 'dealer']);

        // Create automobile and booking
        $automobile = Automobile::factory()->create(['dealer_id' => $dealer->id, 'status' => 'available']);
        $booking = Booking::factory()->create([
            'user_id' => $customer1->id,
            'automobile_id' => $automobile->id,
            'status' => 'pending'
        ]);

        // Customer2 tries to edit customer1's booking (should fail)
        $response = $this->actingAs($customer2)->get(route('bookings.edit', $booking));
        $response->assertRedirect(route('bookings.show', $booking));

        // Customer1 tries to edit their own booking (should succeed)
        $response = $this->actingAs($customer1)->get(route('bookings.edit', $booking));
        $response->assertOk();
    }

    /** @test */
    public function confirmed_bookings_cannot_be_edited_by_customers()
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $dealer = User::factory()->create(['role' => 'dealer']);

        $automobile = Automobile::factory()->create(['dealer_id' => $dealer->id, 'status' => 'available']);
        $booking = Booking::factory()->create([
            'user_id' => $customer->id,
            'automobile_id' => $automobile->id,
            'status' => 'confirmed' // Already confirmed
        ]);

        // Try to edit confirmed booking
        $response = $this->actingAs($customer)->get(route('bookings.edit', $booking));
        $response->assertRedirect(route('bookings.show', $booking));
    }

    /** @test */
    public function booking_requires_future_date()
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $dealer = User::factory()->create(['role' => 'dealer']);
        $automobile = Automobile::factory()->create(['dealer_id' => $dealer->id, 'status' => 'available']);

        // Try to book for yesterday (should fail)
        $response = $this->actingAs($customer)
                         ->post(route('bookings.store'), [
                             'automobile_id' => $automobile->id,
                             'type' => 'test_drive',
                             'scheduled_at' => Carbon::yesterday()->toDateTimeString(),
                             'preferred_time' => 'Morning (9:00 AM - 12:00 PM)'
                         ]);

        $response->assertSessionHasErrors(['scheduled_at']);
    }

    /** @test */
    public function dealers_can_view_bookings_for_their_automobiles()
    {
        $dealer = User::factory()->create(['role' => 'dealer']);
        $customer = User::factory()->create(['role' => 'customer']);

        // Create automobile and booking
        $automobile = Automobile::factory()->create(['dealer_id' => $dealer->id, 'status' => 'available']);
        $booking = Booking::factory()->create([
            'automobile_id' => $automobile->id,
            'user_id' => $customer->id
        ]);

        // Dealer views booking
        $response = $this->actingAs($dealer)->get(route('bookings.show', $booking));
        $response->assertOk();
    }

    /** @test */
    public function dealers_cannot_view_other_dealers_bookings()
    {
        $dealer1 = User::factory()->create(['role' => 'dealer']);
        $dealer2 = User::factory()->create(['role' => 'dealer']);
        $customer = User::factory()->create(['role' => 'customer']);

        // Create automobile for dealer1 and booking
        $automobile = Automobile::factory()->create(['dealer_id' => $dealer1->id, 'status' => 'available']);
        $booking = Booking::factory()->create([
            'automobile_id' => $automobile->id,
            'user_id' => $customer->id
        ]);

        // Dealer2 tries to view dealer1's booking
        $response = $this->actingAs($dealer2)->get(route('bookings.show', $booking));
        $response->assertRedirect(route('bookings.index'));
    }

    /** @test */
    public function booking_conflict_prevention_works()
    {
        $customer1 = User::factory()->create(['role' => 'customer']);
        $customer2 = User::factory()->create(['role' => 'customer']);
        $dealer = User::factory()->create(['role' => 'dealer']);
        $automobile = Automobile::factory()->create(['dealer_id' => $dealer->id, 'status' => 'available']);

        $scheduledDate = Carbon::tomorrow()->toDateTimeString();

        // Create first booking
        $booking1 = Booking::factory()->create([
            'user_id' => $customer1->id,
            'automobile_id' => $automobile->id,
            'scheduled_at' => $scheduledDate,
            'status' => 'confirmed'
        ]);

        // Try to create conflicting booking
        $response = $this->actingAs($customer2)
                         ->post(route('bookings.store'), [
                             'automobile_id' => $automobile->id,
                             'type' => 'test_drive',
                             'scheduled_at' => $scheduledDate,
                             'preferred_time' => 'Morning (9:00 AM - 12:00 PM)'
                         ]);

        $response->assertSessionHasErrors(['scheduled_at']);
    }

    /** @test */
    public function booking_number_is_unique()
    {
        $customer = User::factory()->customer()->create();
        $dealer = User::factory()->dealer()->create();
        $automobile = Automobile::factory()->available()->create(['dealer_id' => $dealer->id]);

        // Create two bookings
        $response1 = $this->actingAs($customer)
                          ->post(route('bookings.store'), [
                              'automobile_id' => $automobile->id,
                              'type' => 'test_drive',
                              'scheduled_at' => Carbon::tomorrow()->toDateTimeString(),
                              'preferred_time' => 'Morning (9:00 AM - 12:00 PM)'
                          ]);

        // Check if the first booking was created successfully
        $this->assertDatabaseCount('bookings', 1);

        $response2 = $this->actingAs($customer)
                          ->post(route('bookings.store'), [
                              'automobile_id' => $automobile->id,
                              'type' => 'reservation',
                              'scheduled_at' => Carbon::tomorrow()->addDay()->toDateTimeString(),
                              'preferred_time' => 'Afternoon (12:00 PM - 5:00 PM)'
                          ]);

        // Check if both bookings were created successfully
        $this->assertDatabaseCount('bookings', 2);

        // Check that booking numbers are different
        $booking1 = Booking::first();
        $booking2 = Booking::skip(1)->first();

        $this->assertNotEquals($booking1->booking_number, $booking2->booking_number);
        $this->assertStringStartsWith('BK-', $booking1->booking_number);
        $this->assertStringStartsWith('BK-', $booking2->booking_number);
    }
}
