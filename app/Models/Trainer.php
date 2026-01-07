<?php

// ============================================
// 2. app/Models/Trainer.php
// ============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trainer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address',
        'specialization',
        'bio',
        'experience_years',
        'certifications',
        'achievements',
        'base_salary',
        'commission_rate',
        'hire_date',
        'working_days',
        'shift_start',
        'shift_end',
        'avatar',
        'gallery',
        'facebook',
        'instagram',
        'youtube',
        'rating',
        'total_reviews',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'certifications' => 'array',
        'working_days' => 'array',
        'gallery' => 'array',
        'base_salary' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'rating' => 'decimal:2',
        'shift_start' => 'datetime:H:i',
        'shift_end' => 'datetime:H:i',
    ];

    // Relationships
    public function classes()
    {
        return $this->hasMany(GymClass::class, 'trainer_id');
    }

    public function activeClasses()
    {
        return $this->hasMany(GymClass::class, 'trainer_id')
            ->where('status', 'active');
    }

    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, GymClass::class, 'trainer_id', 'class_id');
    }

    // Helper Methods
    public function isAvailable()
    {
        return $this->status === 'active';
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar 
            ? asset('storage/' . $this->avatar) 
            : asset('images/default-trainer.png');
    }

    public function totalStudents()
    {
        return $this->bookings()
            ->where('status', 'completed')
            ->distinct('member_id')
            ->count('member_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeBySpecialization($query, $specialization)
    {
        return $query->where('specialization', 'like', '%' . $specialization . '%');
    }

    public function scopeTopRated($query, $limit = 5)
    {
        return $query->where('rating', '>', 4)
            ->orderBy('rating', 'desc')
            ->limit($limit);
    }
}
