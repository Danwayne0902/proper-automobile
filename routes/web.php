<?php

use App\Http\Controllers\AutomobileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\BankAccountController;
use App\Models\Automobile;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
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
                'image' => $car->images && count($car->images) > 0 ? $car->images[0] : null,
                'status' => $car->status
            ];
        });

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'featuredCars' => $featuredCars
    ]);
});

// Public routes for automobile viewing
Route::get('/automobiles/{automobile}/public', [AutomobileController::class, 'publicShow'])->name('automobiles.public.show');

// Test route for debugging
Route::get('/test-automobile/{automobile}', function (App\Models\Automobile $automobile) {
    return response()->json($automobile);
})->name('test.automobile');

// Static Pages
Route::get('/about', function () {
    return Inertia::render('StaticPages/About');
})->name('about');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/faq', function () {
    return Inertia::render('StaticPages/FAQ');
})->name('faq');

Route::get('/privacy', function () {
    return Inertia::render('StaticPages/Privacy');
})->name('privacy');

Route::get('/terms', function () {
    return Inertia::render('StaticPages/Terms');
})->name('terms');

Route::get('/cookies', function () {
    return Inertia::render('StaticPages/Cookies');
})->name('cookies');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Automobile routes
    Route::resource('automobiles', AutomobileController::class);

    // Booking routes
    Route::resource('bookings', BookingController::class);
    Route::patch('bookings/{booking}/status', [BookingController::class, 'updateStatus'])
         ->name('bookings.updateStatus');

    // Transaction routes
    Route::resource('transactions', TransactionController::class);
    Route::patch('transactions/{transaction}/status', [TransactionController::class, 'updateStatus'])
         ->name('transactions.updateStatus');
    Route::get('transactions/{transaction}/invoice', [TransactionController::class, 'generateInvoice'])
         ->name('transactions.invoice');

    // Bank account routes
    Route::resource('bank-accounts', BankAccountController::class);
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UsersController::class);
    Route::resource('reports', ReportsController::class);
    // Settings routes
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::patch('settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('settings/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');

    // Specialized management routes
    Route::get('dealers', [UsersController::class, 'dealers'])->name('dealers.index');
    Route::get('customers', [UsersController::class, 'customers'])->name('customers.index');

    // Export routes
    Route::prefix('export')->name('export.')->group(function () {
        Route::get('automobiles', [ExportController::class, 'automobiles'])->name('automobiles');
        Route::get('users', [ExportController::class, 'users'])->name('users');
        Route::get('bookings', [ExportController::class, 'bookings'])->name('bookings');
        Route::get('transactions', [ExportController::class, 'transactions'])->name('transactions');
        Route::get('sales-report', [ExportController::class, 'salesReport'])->name('sales-report');
    });
});

// Payment confirmation routes - accessible by both admin and dealers
Route::middleware(['auth', 'adminOrDealer'])->group(function () {
    Route::get('reports/payments', [ReportsController::class, 'payments'])->name('reports.payments');
    Route::patch('reports/payments/{transaction}/confirm', [ReportsController::class, 'confirmPayment'])->name('reports.payments.confirm');
});

require __DIR__.'/auth.php';
