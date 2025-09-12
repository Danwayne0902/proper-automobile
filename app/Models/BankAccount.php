<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_name',
        'account_number',
        'bank_name',
        'routing_number',
        'account_type',
        'payment_method',
        'currency',
        'country_code',
        'payment_details',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'payment_details' => 'array', // Cast JSON to array
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor to get formatted account info based on payment method
    public function getFormattedAccountInfoAttribute()
    {
        switch ($this->payment_method) {
            case 'paypal':
                return "PayPal: {$this->account_number}";
            case 'stripe':
                return "Stripe: {$this->account_number}";
            case 'bank_transfer':
            default:
                return "{$this->bank_name} ({$this->account_type}): {$this->account_number}";
        }
    }

    // Scope for active accounts
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for specific payment methods
    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }
}
