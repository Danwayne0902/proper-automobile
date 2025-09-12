<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'automobile_id',
        'transaction_number',
        'amount',
        'type',
        'status',
        'payment_method',
        'description',
        'metadata',
        'processed_at',
        'bank_account_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'processed_at' => 'datetime',
    ];

    /**
     * The user associated with this transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The automobile associated with this transaction
     */
    public function automobile()
    {
        return $this->belongsTo(Automobile::class)->with('dealer');
    }

    /**
     * The booking associated with this transaction
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * The bank account associated with this transaction
     */
    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    /**
     * Generate a unique transaction number
     */
    public static function generateTransactionNumber()
    {
        do {
            $number = 'TXN-' . now()->format('Y') . '-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('transaction_number', $number)->exists());

        return $number;
    }

    /**
     * Scope for completed transactions
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for pending transactions
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for payment transactions
     */
    public function scopePayments($query)
    {
        return $query->where('type', 'payment');
    }
}
