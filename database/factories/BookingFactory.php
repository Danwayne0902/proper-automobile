<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use App\Models\Automobile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition()
    {
        return [
            'user_id' => User::factory()->customer(),
            'automobile_id' => Automobile::factory()->available(),
            'booking_number' => 'BK-' . strtoupper($this->faker->unique()->regexify('[A-Z0-9]{8}')),
            'type' => $this->faker->randomElement(['test_drive', 'reservation', 'inspection']),
            'scheduled_at' => $this->faker->dateTimeBetween('tomorrow', '+1 month'),
            'preferred_time' => $this->faker->randomElement([
                'Morning (9:00 AM - 12:00 PM)',
                'Afternoon (12:00 PM - 5:00 PM)',
                'Evening (5:00 PM - 8:00 PM)',
                'Flexible'
            ]),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
            'notes' => $this->faker->optional()->paragraph(),
            'confirmed_at' => function (array $attributes) {
                return in_array($attributes['status'], ['confirmed', 'completed'])
                    ? $this->faker->dateTimeBetween('-1 week', 'now')
                    : null;
            },
        ];
    }

    /**
     * Indicate that the booking is pending.
     */
    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
                'confirmed_at' => null,
            ];
        });
    }

    /**
     * Indicate that the booking is confirmed.
     */
    public function confirmed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'confirmed',
                'confirmed_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
            ];
        });
    }

    /**
     * Indicate that the booking is completed.
     */
    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'completed',
                'confirmed_at' => $this->faker->dateTimeBetween('-1 month', '-1 week'),
            ];
        });
    }

    /**
     * Indicate that the booking is cancelled.
     */
    public function cancelled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'cancelled',
                'confirmed_at' => null,
            ];
        });
    }

    /**
     * Indicate that the booking is for a test drive.
     */
    public function testDrive()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'test_drive',
            ];
        });
    }

    /**
     * Indicate that the booking is for a reservation.
     */
    public function reservation()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'reservation',
            ];
        });
    }

    /**
     * Indicate that the booking is for an inspection.
     */
    public function inspection()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'inspection',
            ];
        });
    }
}
