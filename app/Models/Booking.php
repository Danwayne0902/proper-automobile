<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'automobile_id',
        'booking_number',
        'type',
        'scheduled_at',
        'preferred_time',
        'notes',
        'status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    /**
     * The user who made this booking
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The automobile being booked
     */
    public function automobile()
    {
        return $this->belongsTo(Automobile::class);
    }

    /**
     * Scope for pending bookings
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for confirmed bookings
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope for completed bookings
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for cancelled bookings
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope for test drive bookings
     */
    public function scopeTestDrive($query)
    {
        return $query->where('type', 'test_drive');
    }

    /**
     * Scope for reservation bookings
     */
    public function scopeReservation($query)
    {
        return $query->where('type', 'reservation');
    }

    /**
     * The transactions associated with this booking
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
