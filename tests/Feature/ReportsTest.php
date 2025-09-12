<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Automobile;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReportsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function admins_can_access_payment_confirmation_page()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $dealer = User::factory()->create(['role' => 'dealer']);
        $customer = User::factory()->create(['role' => 'customer']);
        $automobile = Automobile::factory()->create(['dealer_id' => $dealer->id]);

        // Create a pending transaction
        $transaction = Transaction::factory()->create([
            'user_id' => $customer->id,
            'automobile_id' => $automobile->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($admin)->get(route('reports.payments'));
        $response->assertOk();
        // Check for key elements in the Inertia response
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Reports/Payments')
            ->has('transactions')
        );
    }

    /** @test */
    public function dealers_can_access_payment_confirmation_page()
    {
        $dealer = User::factory()->create(['role' => 'dealer']);
        $customer = User::factory()->create(['role' => 'customer']);
        $automobile = Automobile::factory()->create(['dealer_id' => $dealer->id]);

        // Create a pending transaction for this dealer's automobile
        $transaction = Transaction::factory()->create([
            'user_id' => $customer->id,
            'automobile_id' => $automobile->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($dealer)->get(route('reports.payments'));
        $response->assertOk();
        // Check for key elements in the Inertia response
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Reports/Payments')
            ->has('transactions')
        );
    }

    /** @test */
    public function customers_cannot_access_payment_confirmation_page()
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->get(route('reports.payments'));
        $response->assertForbidden();
    }

    /** @test */
    public function admins_can_confirm_payments()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $dealer = User::factory()->create(['role' => 'dealer']);
        $customer = User::factory()->create(['role' => 'customer']);
        $automobile = Automobile::factory()->create([
            'dealer_id' => $dealer->id,
            'status' => 'available'
        ]);

        // Create a pending transaction
        $transaction = Transaction::factory()->create([
            'user_id' => $customer->id,
            'automobile_id' => $automobile->id,
            'status' => 'pending',
            'type' => 'payment',
            'amount' => 25000
        ]);

        $response = $this->actingAs($admin)->patch(
            route('reports.payments.confirm', $transaction),
            [
                'status' => 'completed',
                'notes' => 'Payment confirmed by admin'
            ]
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'completed'
        ]);

        // Check that automobile status was updated
        $automobile->refresh();
        $this->assertEquals('sold', $automobile->status);
    }

    /** @test */
    public function dealers_can_confirm_payments_for_their_automobiles()
    {
        $dealer = User::factory()->create(['role' => 'dealer']);
        $customer = User::factory()->create(['role' => 'customer']);
        $automobile = Automobile::factory()->create([
            'dealer_id' => $dealer->id,
            'status' => 'available'
        ]);

        // Create a pending transaction for this dealer's automobile
        $transaction = Transaction::factory()->create([
            'user_id' => $customer->id,
            'automobile_id' => $automobile->id,
            'status' => 'pending',
            'type' => 'payment',
            'amount' => 25000
        ]);

        $response = $this->actingAs($dealer)->patch(
            route('reports.payments.confirm', $transaction),
            [
                'status' => 'completed',
                'notes' => 'Payment confirmed by dealer'
            ]
        );

        $response->assertRedirect();
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'completed'
        ]);

        // Check that automobile status was updated
        $automobile->refresh();
        $this->assertEquals('sold', $automobile->status);
    }

    /** @test */
    public function dealers_cannot_confirm_payments_for_other_dealers_automobiles()
    {
        $dealer1 = User::factory()->create(['role' => 'dealer']);
        $dealer2 = User::factory()->create(['role' => 'dealer']);
        $customer = User::factory()->create(['role' => 'customer']);
        $automobile = Automobile::factory()->create([
            'dealer_id' => $dealer1->id,
            'status' => 'available'
        ]);

        // Create a pending transaction for dealer1's automobile
        $transaction = Transaction::factory()->create([
            'user_id' => $customer->id,
            'automobile_id' => $automobile->id,
            'status' => 'pending',
            'type' => 'payment',
            'amount' => 25000
        ]);

        // Dealer2 tries to confirm the payment
        $response = $this->actingAs($dealer2)->patch(
            route('reports.payments.confirm', $transaction),
            [
                'status' => 'completed',
                'notes' => 'Payment confirmed by unauthorized dealer'
            ]
        );

        $response->assertRedirect();
        // The transaction status should not change
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'pending'
        ]);
    }

    /** @test */
    public function payment_confirmation_updates_metadata()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $dealer = User::factory()->create(['role' => 'dealer']);
        $customer = User::factory()->create(['role' => 'customer']);
        $automobile = Automobile::factory()->create(['dealer_id' => $dealer->id]);

        // Create a pending transaction
        $transaction = Transaction::factory()->create([
            'user_id' => $customer->id,
            'automobile_id' => $automobile->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($admin)->patch(
            route('reports.payments.confirm', $transaction),
            [
                'status' => 'completed',
                'notes' => 'Payment confirmed with notes'
            ]
        );

        $response->assertRedirect();
        $transaction->refresh();

        // Check that metadata was updated
        $this->assertArrayHasKey('confirmation_notes', $transaction->metadata);
        $this->assertArrayHasKey('confirmed_by', $transaction->metadata);
        $this->assertArrayHasKey('confirmed_at', $transaction->metadata);
        $this->assertEquals('Payment confirmed with notes', $transaction->metadata['confirmation_notes']);
        $this->assertEquals($admin->id, $transaction->metadata['confirmed_by']);
    }
}
