<?php

// ============================================
// 1. app/Models/Member.php
// ============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', // THÊM FIELD NÀY
        'name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'membership_type',
        'membership_start',
        'membership_end',
        'membership_price',
        'height',
        'weight',
        'health_notes',
        'status',
        'avatar',
    ];

    protected $casts = [
        'membership_start' => 'date',
        'membership_end' => 'date',
        'date_of_birth' => 'date',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'membership_price' => 'decimal:2',
    ];

    // Relationship với User
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    // Relationships khác giữ nguyên
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function activeBookings()
    {
        return $this->hasMany(Booking::class)
            ->whereIn('status', ['pending', 'confirmed']);
    }

    // Helper Methods
    public function isActive()
    {
        return $this->status === 'active' && 
               $this->membership_end >= now();
    }

    public function isExpired()
    {
        return $this->membership_end < now();
    }

    public function daysUntilExpiry()
    {
        return now()->diffInDays($this->membership_end, false);
    }

    public function getAvatarUrlAttribute()
    {
        if (! $this->avatar) {
            return asset('images/default-avatar.png');
        }

        if (str_starts_with($this->avatar, 'http')) {
            return $this->avatar;
        }

        return asset('storage/' . $this->avatar);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('membership_end', '>=', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('membership_end', '<', now());
    }

    public function scopeByMembershipType($query, $type)
    {
        return $query->where('membership_type', $type);
    }
}
