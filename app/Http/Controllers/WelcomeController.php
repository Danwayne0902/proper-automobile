<?php

namespace App\Http\Controllers;

use App\Models\Automobile;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function index()
    {
        // Get featured cars from the database (6 most recent available automobiles)
        $featuredCars = Automobile::with('dealer')
            ->where('status', 'available')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($car) {
                return [
                    'id' => $car->id,
                    'make' => $car->make,
                    'model' => $car->model,
                    'year' => $car->year,
                    'price' => $car->price,
                    'image' => $car->images && count($car->images) > 0 ? '/storage/' . $car->images[0] : null,
                    'status' => $car->status
                ];
            });

        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => app()->version(),
            'phpVersion' => PHP_VERSION,
            'featuredCars' => $featuredCars
        ]);
    }
}
