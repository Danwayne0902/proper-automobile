<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\BankAccount;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all dealer users
        $dealers = User::where('role', 'dealer')->get();

        foreach ($dealers as $dealer) {
            // Add a bank transfer account
            BankAccount::firstOrCreate(
                [
                    'user_id' => $dealer->id,
                    'account_number' => 'BT-' . rand(100000, 999999)
                ],
                [
                    'account_name' => $dealer->name . ' Business Account',
                    'bank_name' => 'Global Bank',
                    'routing_number' => 'ROUTING-' . rand(10000, 99999),
                    'account_type' => 'checking',
                    'payment_method' => 'bank_transfer',
                    'currency' => 'USD',
                    'country_code' => 'US',
                    'is_active' => true
                ]
            );

            // Add a PayPal account
            BankAccount::firstOrCreate(
                [
                    'user_id' => $dealer->id,
                    'account_number' => $dealer->email
                ],
                [
                    'account_name' => $dealer->name . ' PayPal Account',
                    'bank_name' => 'PayPal',
                    'account_type' => 'checking',
                    'payment_method' => 'paypal',
                    'currency' => 'USD',
                    'country_code' => 'US',
                    'is_active' => true
                ]
            );

            // Add a Stripe account for one of the dealers
            if ($dealer->id % 2 == 0) {
                BankAccount::firstOrCreate(
                    [
                        'user_id' => $dealer->id,
                        'account_number' => 'STRIPE-' . strtoupper(uniqid())
                    ],
                    [
                        'account_name' => $dealer->name . ' Stripe Account',
                        'bank_name' => 'Stripe',
                        'account_type' => 'checking',
                        'payment_method' => 'stripe',
                        'currency' => 'USD',
                        'country_code' => 'US',
                        'is_active' => true
                    ]
                );
            }
        }

        $this->command->info('Payment methods seeded successfully!');
    }
}
