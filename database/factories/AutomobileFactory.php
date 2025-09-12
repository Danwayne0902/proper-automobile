<?php

namespace Database\Factories;

use App\Models\Automobile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AutomobileFactory extends Factory
{
    protected $model = Automobile::class;

    public function definition()
    {
        $makes = ['Toyota', 'Honda', 'Ford', 'Chevrolet', 'BMW', 'Mercedes-Benz', 'Audi', 'Nissan', 'Volkswagen', 'Hyundai'];
        $models = [
            'Toyota' => ['Camry', 'Corolla', 'RAV4', 'Highlander', 'Prius'],
            'Honda' => ['Civic', 'Accord', 'CR-V', 'Pilot', 'Fit'],
            'Ford' => ['F-150', 'Escape', 'Explorer', 'Mustang', 'Focus'],
            'Chevrolet' => ['Silverado', 'Equinox', 'Malibu', 'Camaro', 'Cruze'],
            'BMW' => ['3 Series', '5 Series', 'X3', 'X5', 'i3'],
        ];

        $make = $this->faker->randomElement($makes);
        $availableModels = $models[$make] ?? ['Model 1', 'Model 2', 'Model 3'];
        $model = $this->faker->randomElement($availableModels);

        return [
            'dealer_id' => User::factory()->dealer(),
            'make' => $make,
            'model' => $model,
            'year' => $this->faker->numberBetween(2015, 2024),
            'vin' => strtoupper($this->faker->unique()->regexify('[A-Z0-9]{17}')),
            'price' => $this->faker->numberBetween(15000, 80000),
            'mileage' => $this->faker->numberBetween(0, 150000),
            'color' => $this->faker->randomElement(['White', 'Black', 'Silver', 'Blue', 'Red', 'Gray', 'Green']),
            'body_type' => $this->faker->randomElement(['sedan', 'suv', 'hatchback', 'truck', 'coupe', 'convertible']),
            'fuel_type' => $this->faker->randomElement(['gasoline', 'diesel', 'hybrid', 'electric']),
            'transmission' => $this->faker->randomElement(['manual', 'automatic', 'cvt']),
            'engine_size' => $this->faker->optional()->randomFloat(1, 1.0, 6.0) . 'L',
            'status' => $this->faker->randomElement(['available', 'sold', 'reserved', 'maintenance']),
            'description' => $this->faker->optional()->paragraph(),
            'images' => $this->faker->optional()->randomElements([
                'automobiles/sample1.jpg',
                'automobiles/sample2.jpg',
                'automobiles/sample3.jpg'
            ], $this->faker->numberBetween(0, 3)),
        ];
    }

    /**
     * Indicate that the automobile is available.
     */
    public function available()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'available',
            ];
        });
    }

    /**
     * Indicate that the automobile is sold.
     */
    public function sold()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'sold',
            ];
        });
    }

    /**
     * Indicate that the automobile is reserved.
     */
    public function reserved()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'reserved',
            ];
        });
    }
}
