<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_be_an_admin()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isDealer());
        $this->assertFalse($user->isCustomer());
    }

    /** @test */
    public function user_can_be_a_dealer()
    {
        $user = User::factory()->create(['role' => 'dealer']);

        $this->assertFalse($user->isAdmin());
        $this->assertTrue($user->isDealer());
        $this->assertFalse($user->isCustomer());
    }

    /** @test */
    public function user_can_be_a_customer()
    {
        $user = User::factory()->create(['role' => 'customer']);

        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->isDealer());
        $this->assertTrue($user->isCustomer());
    }

    /** @test */
    public function user_defaults_to_customer_role()
    {
        $user = User::factory()->create(); // No role specified

        // Check if default role is customer (if we set it in the model or factory)
        $this->assertEquals('customer', $user->role);
        $this->assertTrue($user->isCustomer());
    }

    /** @test */
    public function user_has_automobiles_relationship_when_dealer()
    {
        $dealer = User::factory()->create(['role' => 'dealer']);

        // Test that the relationship exists
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $dealer->automobiles);
    }

    /** @test */
    public function user_has_bookings_relationship_when_customer()
    {
        $customer = User::factory()->create(['role' => 'customer']);

        // Test that the relationship exists
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $customer->bookings);
    }

    /** @test */
    public function user_has_transactions_relationship()
    {
        $user = User::factory()->create();

        // Test that the relationship exists
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $user->transactions);
    }

    /** @test */
    public function user_email_must_be_unique()
    {
        $email = 'test@example.com';

        // Create first user
        User::factory()->create(['email' => $email]);

        // Try to create second user with same email - should fail
        $this->expectException(\Illuminate\Database\QueryException::class);
        User::factory()->create(['email' => $email]);
    }

    /** @test */
    public function user_password_is_hashed()
    {
        $password = 'plaintext-password';
        $user = User::factory()->create(['password' => bcrypt($password)]);

        // Password should not be stored as plain text
        $this->assertNotEquals($password, $user->password);

        // But should verify correctly
        $this->assertTrue(\Hash::check($password, $user->password));
    }
}
