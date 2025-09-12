<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Automobile;
use App\Models\Transaction;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function customers_can_create_transactions()
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $dealer = User::factory()->create(['role' => 'dealer']);
        $automobile = Automobile::factory()->create([
            'dealer_id' => $dealer->id,
            'status' => 'available',
            'price' => 25000
        ]);

        $transactionData = [
            'automobile_id' => $automobile->id,
            'type' => 'deposit',
            'amount' => 5000,
            'payment_method' => 'credit_card',
            'description' => 'Deposit for Toyota Camry'
        ];

        $response = $this->actingAs($customer)
                         ->post(route('transactions.store'), $transactionData);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $customer->id,
            'automobile_id' => $automobile->id,
            'type' => 'deposit',
            'amount' => 5000,
            'status' => 'pending'
        ]);

        $response->assertRedirect();
    }

    /** @test */
    public function dealers_cannot_create_transactions()
    {
        $dealer = User::factory()->create(['role' => 'dealer']);

        $response = $this->actingAs($dealer)->get(route('transactions.create'));
        $response->assertRedirect(route('transactions.index'));
    }

    /** @test */
    public function payment_amount_cannot_exceed_automobile_price()
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $dealer = User::factory()->create(['role' => 'dealer']);
        $automobile = Automobile::factory()->create([
            'dealer_id' => $dealer->id,
            'price' => 25000
        ]);

        // Try to pay more than automobile price
        $response = $this->actingAs($customer)
                         ->post(route('transactions.store'), [
                             'automobile_id' => $automobile->id,
                             'type' => 'payment',
                             'amount' => 30000, // More than price
                             'payment_method' => 'credit_card'
                         ]);

        $response->assertSessionHasErrors(['amount']);
    }

    /** @test */
    public function deposit_amount_cannot_exceed_fifty_percent()
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $dealer = User::factory()->create(['role' => 'dealer']);
        $automobile = Automobile::factory()->create([
            'dealer_id' => $dealer->id,
            'price' => 25000
        ]);

        // Try to deposit more than 50%
        $response = $this->actingAs($customer)
                         ->post(route('transactions.store'), [
                             'automobile_id' => $automobile->id,
                             'type' => 'deposit',
                             'amount' => 15000, // More than 50%
                             'payment_method' => 'credit_card'
                         ]);

        $response->assertSessionHasErrors(['amount']);
    }

    /** @test */
    public function dealers_can_update_transaction_status()
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $dealer = User::factory()->create(['role' => 'dealer']);
        $automobile = Automobile::factory()->create(['dealer_id' => $dealer->id]);

        $transaction = Transaction::factory()->create([
            'user_id' => $customer->id,
            'automobile_id' => $automobile->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($dealer)
                         ->patch(route('transactions.updateStatus', $transaction), [
                             'status' => 'completed',
                             'notes' => 'Payment processed successfully'
                         ]);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'completed'
        ]);

        $response->assertRedirect();
    }

    /** @test */
    public function customers_cannot_update_transaction_status()
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $dealer = User::factory()->create(['role' => 'dealer']);
        $automobile = Automobile::factory()->create(['dealer_id' => $dealer->id]);

        $transaction = Transaction::factory()->create([
            'user_id' => $customer->id,
            'automobile_id' => $automobile->id
        ]);

        $response = $this->actingAs($customer)
                         ->patch(route('transactions.updateStatus', $transaction), [
                             'status' => 'completed'
                         ]);

        $response->assertRedirect(route('transactions.show', $transaction));
    }

    /** @test */
    public function automobile_status_updates_when_transaction_completed()
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $dealer = User::factory()->create(['role' => 'dealer']);
        $automobile = Automobile::factory()->create([
            'dealer_id' => $dealer->id,
            'status' => 'available',
            'price' => 25000
        ]);

        // Create full payment transaction
        $transaction = Transaction::factory()->create([
            'user_id' => $customer->id,
            'automobile_id' => $automobile->id,
            'type' => 'payment',
            'amount' => 25000,
            'status' => 'pending'
        ]);

        // Complete the transaction
        $this->actingAs($dealer)
             ->patch(route('transactions.updateStatus', $transaction), [
                 'status' => 'completed'
             ]);

        // Check automobile status changed to sold
        $automobile->refresh();
        $this->assertEquals('sold', $automobile->status);
    }

    /** @test */
    public function transaction_number_is_unique()
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $dealer = User::factory()->create(['role' => 'dealer']);
        $automobile = Automobile::factory()->create(['dealer_id' => $dealer->id]);

        // Create two transactions
        $transaction1 = Transaction::factory()->create([
            'user_id' => $customer->id,
            'automobile_id' => $automobile->id
        ]);

        $transaction2 = Transaction::factory()->create([
            'user_id' => $customer->id,
            'automobile_id' => $automobile->id
        ]);

        $this->assertNotEquals($transaction1->transaction_number, $transaction2->transaction_number);
        $this->assertStringStartsWith('TXN-', $transaction1->transaction_number);
        $this->assertStringStartsWith('TXN-', $transaction2->transaction_number);
    }

    /** @test */
    public function customers_can_only_view_their_own_transactions()
    {
        $customer1 = User::factory()->create(['role' => 'customer']);
        $customer2 = User::factory()->create(['role' => 'customer']);
        $dealer = User::factory()->create(['role' => 'dealer']);
        $automobile = Automobile::factory()->create(['dealer_id' => $dealer->id]);

        $transaction = Transaction::factory()->create([
            'user_id' => $customer1->id,
            'automobile_id' => $automobile->id
        ]);

        // Customer2 tries to view customer1's transaction
        $response = $this->actingAs($customer2)->get(route('transactions.show', $transaction));
        $response->assertRedirect(route('transactions.index'));

        // Customer1 views their own transaction
        $response = $this->actingAs($customer1)->get(route('transactions.show', $transaction));
        $response->assertOk();
    }

    /** @test */
    public function dealers_can_view_transactions_for_their_automobiles()
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $dealer = User::factory()->create(['role' => 'dealer']);
        $automobile = Automobile::factory()->create(['dealer_id' => $dealer->id]);

        $transaction = Transaction::factory()->create([
            'user_id' => $customer->id,
            'automobile_id' => $automobile->id
        ]);

        $response = $this->actingAs($dealer)->get(route('transactions.show', $transaction));
        $response->assertOk();
    }
}
