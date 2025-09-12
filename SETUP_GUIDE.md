# Laravel + Inertia.js + Vue 2 Automobile Application Setup Guide

## Project Overview
A comprehensive automobile application built with Laravel backend, Inertia.js for seamless SPA experience, and Vue 2 with Options API for the frontend. The application supports role-based access for admins, dealers, and customers with full automobile management capabilities.

## Installation Commands

### 1. Initial Laravel Project Setup
```bash
composer create-project laravel/laravel proper-automobile --prefer-dist
cd proper-automobile

# Install Inertia.js for Laravel
composer require inertiajs/inertia-laravel

# Install Vue 2 and Inertia frontend dependencies
npm install vue@2 @inertiajs/inertia @inertiajs/inertia-vue @inertiajs/progress
npm install --save-dev @vitejs/plugin-vue2 vue-template-compiler

# Install Laravel Breeze for authentication
composer require laravel/breeze --dev

# Configure environment
cp .env.example .env
php artisan key:generate

# Generate Inertia middleware
php artisan inertia:middleware
```

### 2. Database Configuration
Configure your `.env` file with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=proper_automobile
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## Folder Structure

```
proper-automobile/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AutomobileController.php
│   │   │   ├── BookingController.php
│   │   │   ├── TransactionController.php
│   │   │   └── DashboardController.php
│   │   └── Middleware/
│   │       ├── HandleInertiaRequests.php
│   │       ├── AdminMiddleware.php
│   │       ├── DealerMiddleware.php
│   │       └── CustomerMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Automobile.php
│       ├── Booking.php
│       └── Transaction.php
├── database/
│   └── migrations/
│       ├── 0001_01_01_000000_create_users_table.php
│       ├── 2025_09_08_081835_create_automobiles_table.php
│       ├── 2025_09_08_081859_create_bookings_table.php
│       └── 2025_09_08_081924_create_transactions_table.php
├── resources/
│   ├── js/
│   │   ├── Components/
│   │   │   ├── Layout/
│   │   │   │   ├── AppLayout.vue
│   │   │   │   ├── Navbar.vue
│   │   │   │   ├── Sidebar.vue
│   │   │   │   └── Footer.vue
│   │   │   ├── Forms/
│   │   │   │   ├── AutomobileForm.vue
│   │   │   │   ├── BookingForm.vue
│   │   │   │   └── TransactionForm.vue
│   │   │   └── UI/
│   │   │       ├── Button.vue
│   │   │       ├── Input.vue
│   │   │       └── Modal.vue
│   │   ├── Pages/
│   │   │   ├── Auth/
│   │   │   │   ├── Login.vue
│   │   │   │   ├── Register.vue
│   │   │   │   └── ForgotPassword.vue
│   │   │   ├── Dashboard/
│   │   │   │   ├── Index.vue
│   │   │   │   ├── Admin.vue
│   │   │   │   ├── Dealer.vue
│   │   │   │   └── Customer.vue
│   │   │   ├── Automobiles/
│   │   │   │   ├── Index.vue
│   │   │   │   ├── Show.vue
│   │   │   │   ├── Create.vue
│   │   │   │   └── Edit.vue
│   │   │   ├── Bookings/
│   │   │   │   ├── Index.vue
│   │   │   │   ├── Show.vue
│   │   │   │   └── Create.vue
│   │   │   └── Transactions/
│   │   │       ├── Index.vue
│   │   │       ├── Show.vue
│   │   │       └── Create.vue
│   │   └── app.js
│   └── views/
│       └── app.blade.php
└── routes/
    └── web.php
```

## Database Schema

### Users Table (Enhanced)
- id (primary key)
- name (string)
- email (string, unique)
- email_verified_at (timestamp, nullable)
- password (string)
- **role (enum: 'admin', 'dealer', 'customer', default: 'customer')**
- **phone (string, nullable)**
- **address (text, nullable)**
- remember_token
- **soft_deletes**
- timestamps

### Automobiles Table
- id (primary key)
- make (string)
- model (string)
- year (integer)
- price (decimal 12,2)
- mileage (integer, nullable)
- description (text, nullable)
- color (string, nullable)
- transmission (string, nullable)
- fuel_type (string, nullable)
- engine_size (integer, nullable)
- images (json, nullable)
- status (enum: 'available', 'sold', 'reserved', 'maintenance', default: 'available')
- dealer_id (foreign key to users)
- soft_deletes
- timestamps

### Bookings Table
- id (primary key)
- user_id (foreign key to users)
- automobile_id (foreign key to automobiles)
- type (enum: 'test_drive', 'reservation')
- scheduled_at (datetime)
- notes (text, nullable)
- status (enum: 'pending', 'approved', 'completed', 'cancelled', default: 'pending')
- soft_deletes
- timestamps

### Transactions Table
- id (primary key)
- user_id (foreign key to users)
- automobile_id (foreign key to automobiles)
- transaction_number (string, unique)
- amount (decimal 12,2)
- type (enum: 'payment', 'refund', 'deposit')
- status (enum: 'pending', 'completed', 'failed', 'cancelled', default: 'pending')
- payment_method (string, nullable)
- description (text, nullable)
- metadata (json, nullable)
- processed_at (datetime, nullable)
- soft_deletes
- timestamps

## Key Configuration Files

### 1. Vite Configuration (vite.config.js)
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue2 from '@vitejs/plugin-vue2';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue2(),
        tailwindcss(),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
});
```

### 2. Main Vue Application (resources/js/app.js)
```javascript
import Vue from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue'
import { InertiaProgress } from '@inertiajs/progress'

// Import TailwindCSS
import '../css/app.css'

InertiaProgress.init({ color: '#4F46E5' })

createInertiaApp({
    resolve: (name) => {
        const pages = require.context('./Pages', true, /\.vue$/i)
        return pages(`./\\${name}.vue`)
    },
    setup({ el, App, props }) {
        return new Vue({
            render: h => h(App, props),
        }).$mount(el)
    },
})
```

### 3. Main Layout Template (resources/views/app.blade.php)
```html
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>{{ config('app.name', 'Proper Automobile') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
</head>
<body class="font-sans antialiased">
    @inertia
</body>
</html>
```

## Model Relationships

### User Model (app/Models/User.php)
```php
<?php

namespace App\\Models;

use Illuminate\\Database\\Eloquent\\Factories\\HasFactory;
use Illuminate\\Database\\Eloquent\\SoftDeletes;
use Illuminate\\Foundation\\Auth\\User as Authenticatable;
use Illuminate\\Notifications\\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'address',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role helper methods
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isDealer(): bool { return $this->role === 'dealer'; }
    public function isCustomer(): bool { return $this->role === 'customer'; }

    // Relationships
    public function automobiles() { return $this->hasMany(Automobile::class, 'dealer_id'); }
    public function bookings() { return $this->hasMany(Booking::class); }
    public function transactions() { return $this->hasMany(Transaction::class); }
}
```

## Next Steps for Implementation

### 1. Install Laravel Breeze with Inertia
```bash
php artisan breeze:install vue --ssr
```

### 2. Create Controllers
```bash
php artisan make:controller AutomobileController --resource
php artisan make:controller BookingController --resource
php artisan make:controller TransactionController --resource
php artisan make:controller DashboardController
```

### 3. Create Middleware for Role-Based Access
```bash
php artisan make:middleware AdminMiddleware
php artisan make:middleware DealerMiddleware  
php artisan make:middleware CustomerMiddleware
```

### 4. Run Migrations
```bash
php artisan migrate
```

### 5. Install Frontend Dependencies and Build
```bash
npm install && npm run dev
```

## Features to Implement

### Authentication System
- ✅ User registration and login
- ✅ Role-based authentication (admin/dealer/customer)
- ✅ Password reset functionality
- ✅ Email verification

### Automobile Management
- ✅ CRUD operations for automobiles
- ✅ Image upload and management
- ✅ Search and filtering (price, year, make, model)
- ✅ Status management (available, sold, reserved)

### Booking System
- ✅ Test drive scheduling
- ✅ Automobile reservations
- ✅ Booking status tracking
- ✅ Calendar integration

### Transaction Management
- ✅ Payment recording
- ✅ Invoice generation
- ✅ Transaction history
- ✅ Refund processing

### Role-Specific Features
- **Admin Dashboard**: Analytics, user management, system overview
- **Dealer Dashboard**: Automobile management, booking approvals, sales tracking
- **Customer Dashboard**: Browse cars, manage bookings, view transactions

### Advanced Features
- 📊 Analytics dashboard with charts
- 📧 Email notifications
- 📱 Responsive design
- 🔍 Advanced search with filters
- 📄 PDF invoice generation
- 📸 Image optimization and storage

## Testing Strategy

### Unit Tests
```bash
# Test Models
php artisan make:test UserTest --unit
php artisan make:test AutomobileTest --unit
php artisan make:test BookingTest --unit
php artisan make:test TransactionTest --unit
```

### Feature Tests
```bash
# Test Controllers and Business Logic
php artisan make:test AutomobileCRUDTest
php artisan make:test BookingSystemTest
php artisan make:test AuthenticationTest
php artisan make:test RoleBasedAccessTest
```

## Deployment Instructions

### Shared Hosting Deployment
1. Upload files to public_html directory
2. Move contents of /public to root directory
3. Update index.php to point to correct paths
4. Set up database and update .env
5. Run composer install --optimize-autoloader --no-dev
6. Run php artisan config:cache
7. Run php artisan route:cache
8. Run php artisan view:cache

### VPS Deployment
1. Set up server with PHP 8.1+, MySQL, and web server
2. Configure domain and SSL
3. Clone repository and set permissions
4. Install dependencies and run migrations
5. Set up queue workers and cron jobs
6. Configure file storage and backups

## Security Considerations
- ✅ CSRF protection enabled
- ✅ SQL injection prevention through Eloquent ORM
- ✅ XSS protection through Vue.js escaping
- ✅ Role-based access control
- ✅ File upload validation and sanitization
- ✅ Input validation on both client and server side

This comprehensive setup provides a solid foundation for a scalable automobile application with modern web technologies.
