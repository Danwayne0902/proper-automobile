<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Proper Automobile Admin',
            'email' => 'admin@properautomobile.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'phone' => '+2347039481762',
            'address' => '123 Luxury Auto Drive, Premium District, Car City, CC 12345',
        ]);
    }
}
