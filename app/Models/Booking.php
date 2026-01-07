<?php

// ============================================
// 4. app/Models/Booking.php
// ============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'member_id',
        'class_id',
        'booking_date',
        'booking_time',
        'booking_code',
        'price',
        'payment_status',
        'payment_method',
        'payment_date',
        'status',
        'cancellation_reason',
        'cancelled_at',
        'member_notes',
        'admin_notes',
        'is_checked_in',
        'checked_in_at',
        'rating',
        'review',
        'reviewed_at',
        'reminder_sent',
        'reminder_sent_at',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'booking_time' => 'datetime:H:i',
        'payment_date' => 'datetime',
        'cancelled_at' => 'datetime',
        'checked_in_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
        'price' => 'decimal:2',
        'is_checked_in' => 'boolean',
        'reminder_sent' => 'boolean',
    ];

    // Auto generate booking code
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = 'BK' . date('Ymd') . str_pad(Booking::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });

        static::created(function ($booking) {
            // Update current participants count
            $booking->gymClass->updateCurrentParticipants();
        });

        static::updated(function ($booking) {
            // Update current participants count when status changes
            if ($booking->isDirty('status')) {
                $booking->gymClass->updateCurrentParticipants();
            }
        });
    }

    // Relationships
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function gymClass()
    {
        return $this->belongsTo(GymClass::class, 'class_id');
    }

    // Helper Methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function canCancel()
    {
        return in_array($this->status, ['pending', 'confirmed']) &&
               $this->booking_date > now()->addHours(24);
    }

    public function canCheckIn()
    {
        return $this->status === 'confirmed' &&
               !$this->is_checked_in &&
               $this->booking_date->isToday();
    }

    public function canReview()
    {
        return $this->status === 'completed' &&
               $this->rating === null;
    }

    public function checkIn()
    {
        $this->update([
            'is_checked_in' => true,
            'checked_in_at' => now(),
        ]);
    }

    public function complete()
    {
        $this->update(['status' => 'completed']);
    }

    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
            'cancelled_at' => now(),
        ]);
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('booking_date', '>', now())
            ->whereIn('status', ['pending', 'confirmed']);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('booking_date', today())
            ->whereIn('status', ['pending', 'confirmed']);
    }

    public function scopePast($query)
    {
        return $query->where('booking_date', '<', now());
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPaymentStatus($query, $paymentStatus)
    {
        return $query->where('payment_status', $paymentStatus);
    }

    public function scopeNeedReminder($query)
    {
        return $query->where('reminder_sent', false)
            ->where('booking_date', '>', now())
            ->where('booking_date', '<=', now()->addHours(24))
            ->whereIn('status', ['pending', 'confirmed']);
    }
}