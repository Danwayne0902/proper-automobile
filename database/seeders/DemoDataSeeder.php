<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Automobile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@proper-automobile.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+234 (705) 340-4846',
                'address' => '123 Luxury Auto Drive, Premium District, Car City, CC 12345',
                'email_verified_at' => now()
            ]
        );

        // Create Dealer Users
        $dealer1 = User::firstOrCreate(
            ['email' => 'dealer1@proper-automobile.com'],
            [
                'name' => 'John Smith',
                'password' => Hash::make('password'),
                'role' => 'dealer',
                'phone' => '+1 (555) 234-5678',
                'address' => '456 Dealer Avenue, Dealer City, DC 23456',
                'email_verified_at' => now()
            ]
        );

        $dealer2 = User::firstOrCreate(
            ['email' => 'dealer2@proper-automobile.com'],
            [
                'name' => 'Sarah Johnson',
                'password' => Hash::make('password'),
                'role' => 'dealer',
                'phone' => '+1 (555) 345-6789',
                'address' => '789 Sales Boulevard, Sales City, SC 34567',
                'email_verified_at' => now()
            ]
        );

        // Create Customer Users
        for ($i = 1; $i <= 10; $i++) {
            User::firstOrCreate(
                ['email' => "customer{$i}@example.com"],
                [
                    'name' => "Customer {$i}",
                    'password' => Hash::make('password'),
                    'role' => 'customer',
                    'phone' => '+1 (555) ' . str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT) . '-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                    'address' => rand(100, 999) . " Customer Street, Customer City, CC " . rand(10000, 99999),
                    'email_verified_at' => now()
                ]
            );
        }

        // Create Demo Automobiles with images
        $demoAutos = [
            [
                'dealer_id' => $dealer1->id,
                'make' => 'Mercedes-Benz',
                'model' => 'C-Class',
                'year' => 2024,
                'vin' => 'WDDGF4HB1PA' . rand(100000, 999999),
                'price' => 48000,
                'mileage' => 0,
                'color' => 'Silver',
                'body_type' => 'Sedan',
                'fuel_type' => 'Gasoline',
                'transmission' => 'Automatic',
                'engine' => '2.0L Turbo',
                'description' => 'Luxury sedan with premium features and exceptional performance.',
                'features' => json_encode(['Leather Seats', 'Navigation', 'Sunroof', 'Backup Camera']),
                'status' => 'available',
                'images' => json_encode([
                    'automobiles/mercedes-c-class-1.jpg',
                    'automobiles/mercedes-c-class-2.jpg',
                    'automobiles/mercedes-c-class-3.jpg'
                ])
            ],
            [
                'dealer_id' => $dealer1->id,
                'make' => 'Audi',
                'model' => 'Q5 Sportback',
                'year' => 2024,
                'vin' => 'WA1AAAFY1N2' . rand(100000, 999999),
                'price' => 55000,
                'mileage' => 0,
                'color' => 'Blue',
                'body_type' => 'SUV',
                'fuel_type' => 'Gasoline',
                'transmission' => 'Automatic',
                'engine' => '2.0L TFSI',
                'description' => 'Sporty SUV with coupe-like styling and advanced technology.',
                'features' => json_encode(['Quattro AWD', 'Virtual Cockpit', 'Premium Sound', 'Adaptive Cruise']),
                'status' => 'available',
                'images' => json_encode([
                    'automobiles/audi-q5-1.jpg',
                    'automobiles/audi-q5-2.jpg',
                    'automobiles/audi-q5-3.jpg'
                ])
            ],
            [
                'dealer_id' => $dealer2->id,
                'make' => 'Toyota',
                'model' => 'Camry',
                'year' => 2024,
                'vin' => '4T1G11AK8PU' . rand(100000, 999999),
                'price' => 35000,
                'mileage' => 0,
                'color' => 'White',
                'body_type' => 'Sedan',
                'fuel_type' => 'Hybrid',
                'transmission' => 'CVT',
                'engine' => '2.5L Hybrid',
                'description' => 'Reliable hybrid sedan with excellent fuel economy.',
                'features' => json_encode(['Toyota Safety Sense', 'Apple CarPlay', 'LED Headlights', 'Wireless Charging']),
                'status' => 'available',
                'images' => json_encode([
                    'automobiles/toyota-camry-1.jpg',
                    'automobiles/toyota-camry-2.jpg',
                    'automobiles/toyota-camry-3.jpg'
                ])
            ],
            [
                'dealer_id' => $dealer2->id,
                'make' => 'Mercedes-Benz',
                'model' => 'AMG CLE 53',
                'year' => 2024,
                'vin' => 'WDDGF4HB1PA' . rand(100000, 999999),
                'price' => 78000,
                'mileage' => 0,
                'color' => 'Black',
                'body_type' => 'Coupe',
                'fuel_type' => 'Gasoline',
                'transmission' => 'Automatic',
                'engine' => '3.0L AMG',
                'description' => 'High-performance luxury coupe with AMG engineering.',
                'features' => json_encode(['AMG Performance Package', 'MBUX Infotainment', 'Burmester Sound', 'Performance Seats']),
                'status' => 'available',
                'images' => json_encode([
                    'automobiles/mercedes-amg-cle-1.jpg',
                    'automobiles/mercedes-amg-cle-2.jpg',
                    'automobiles/mercedes-amg-cle-3.jpg'
                ])
            ],
            [
                'dealer_id' => $dealer1->id,
                'make' => 'Mercedes-Benz',
                'model' => 'G-550 SUV',
                'year' => 2024,
                'vin' => 'WDCYC2HF1PX' . rand(100000, 999999),
                'price' => 135000,
                'mileage' => 0,
                'color' => 'Black',
                'body_type' => 'SUV',
                'fuel_type' => 'Gasoline',
                'transmission' => 'Automatic',
                'engine' => '4.0L V8',
                'description' => 'Iconic luxury SUV with unmatched off-road capability.',
                'features' => json_encode(['G-Mode', 'Luxury Interior', 'Advanced 4WD', 'Premium Sound System']),
                'status' => 'available',
                'images' => json_encode([
                    'automobiles/mercedes-g550-1.jpg',
                    'automobiles/mercedes-g550-2.jpg',
                    'automobiles/mercedes-g550-3.jpg'
                ])
            ],
            [
                'dealer_id' => $dealer2->id,
                'make' => 'Audi',
                'model' => 'A4 Sedan',
                'year' => 2024,
                'vin' => 'WAUENAF40PA' . rand(100000, 999999),
                'price' => 42000,
                'mileage' => 0,
                'color' => 'Red',
                'body_type' => 'Sedan',
                'fuel_type' => 'Gasoline',
                'transmission' => 'Automatic',
                'engine' => '2.0L TFSI',
                'description' => 'Elegant sedan with sophisticated design and performance.',
                'features' => json_encode(['Quattro AWD', 'MMI Navigation', 'LED Matrix Headlights', 'Bang & Olufsen Sound']),
                'status' => 'available',
                'images' => json_encode([
                    'automobiles/audi-a4-1.jpg',
                    'automobiles/audi-a4-2.jpg',
                    'automobiles/audi-a4-3.jpg'
                ])
            ]
        ];

        foreach ($demoAutos as $autoData) {
            // Convert images from JSON string to array
            $images = json_decode($autoData['images'], true);
            unset($autoData['images']);

            $automobile = Automobile::firstOrCreate(
                ['vin' => $autoData['vin']],
                $autoData
            );

            // Set images
            $automobile->images = $images;
            $automobile->save();
        }

        $this->command->info('Demo data created successfully!');
        $this->command->info('Admin: admin@proper-automobile.com / password');
        $this->command->info('Dealer: dealer1@proper-automobile.com / password');
        $this->command->info('Customer: customer1@example.com / password');
    }
}
