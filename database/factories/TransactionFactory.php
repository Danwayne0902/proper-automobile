<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Automobile;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        return [
            'user_id' => User::factory()->create(['role' => 'customer'])->id,
            'automobile_id' => Automobile::factory()->available()->create()->id,
            'booking_id' => null, // Optional
            'transaction_number' => 'TXN-' . date('Y') . '-' . strtoupper($this->faker->unique()->regexify('[A-Z0-9]{6}')),
            'type' => $this->faker->randomElement(['payment', 'deposit', 'refund']),
            'amount' => $this->faker->numberBetween(1000, 50000),
            'status' => $this->faker->randomElement(['pending', 'processing', 'completed', 'failed', 'refunded']),
            'payment_method' => $this->faker->randomElement(['credit_card', 'bank_transfer', 'cash', 'check']),
            'description' => $this->faker->optional()->paragraph(),
            'processed_at' => function (array $attributes) {
                return in_array($attributes['status'], ['completed', 'failed', 'refunded'])
                    ? $this->faker->dateTimeBetween('-1 month', 'now')
                    : null;
            },
        ];
    }

    /**
     * Indicate that the transaction is pending.
     */
    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
                'processed_at' => null,
            ];
        });
    }

    /**
     * Indicate that the transaction is processing.
     */
    public function processing()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'processing',
                'processed_at' => null,
            ];
        });
    }

    /**
     * Indicate that the transaction is completed.
     */
    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'completed',
                'processed_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            ];
        });
    }

    /**
     * Indicate that the transaction failed.
     */
    public function failed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'failed',
                'processed_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            ];
        });
    }

    /**
     * Indicate that the transaction is a payment.
     */
    public function payment()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'payment',
            ];
        });
    }

    /**
     * Indicate that the transaction is a deposit.
     */
    public function deposit()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'deposit',
            ];
        });
    }

    /**
     * Indicate that the transaction is a refund.
     */
    public function refund()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'refund',
            ];
        });
    }

    /**
     * Indicate that the transaction is paid by credit card.
     */
    public function creditCard()
    {
        return $this->state(function (array $attributes) {
            return [
                'payment_method' => 'credit_card',
            ];
        });
    }

    /**
     * Indicate that the transaction is paid by bank transfer.
     */
    public function bankTransfer()
    {
        return $this->state(function (array $attributes) {
            return [
                'payment_method' => 'bank_transfer',
            ];
        });
    }
}
