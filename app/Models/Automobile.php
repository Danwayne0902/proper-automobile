<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Automobile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'make',
        'model',
        'year',
        'vin',
        'price',
        'mileage',
        'description',
        'color',
        'body_type',
        'exterior_color',
        'interior_color',
        'condition',
        'transmission',
        'fuel_type',
        'engine_size',
        'engine',
        'features',
        'images',
        'status',
        'dealer_id',
    ];

    protected $casts = [
        'images' => 'array',
        'features' => 'array',
        'price' => 'decimal:2',
    ];

    /**
     * The dealer who owns this automobile
     */
    public function dealer()
    {
        return $this->belongsTo(User::class, 'dealer_id');
    }

    /**
     * Bookings for this automobile
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Transactions for this automobile
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Scope for available automobiles
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope for filtering by price range
     */
    public function scopeFilterByPrice($query, $min = null, $max = null)
    {
        if ($min) {
            $query->where('price', '>=', $min);
        }
        if ($max) {
            $query->where('price', '<=', $max);
        }
        return $query;
    }

    /**
     * Scope for filtering by year range
     */
    public function scopeFilterByYear($query, $min = null, $max = null)
    {
        if ($min) {
            $query->where('year', '>=', $min);
        }
        if ($max) {
            $query->where('year', '<=', $max);
        }
        return $query;
    }
}
