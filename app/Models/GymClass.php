<?php

// ============================================
// 3. app/Models/GymClass.php
// ============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class GymClass extends Model
{
    use SoftDeletes;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'benefits',
        'trainer_id',
        'level',
        'category',
        'duration',
        'start_time',
        'end_time',
        'days_of_week',
        'start_date',
        'end_date',
        'max_participants',
        'min_participants',
        'current_participants',
        'price',
        'package_price',
        'is_free_trial',
        'room',
        'location',
        'image',
        'video_url',
        'gallery',
        'requirements',
        'calories_burn',
        'status',
        'is_featured',
        'is_online',
        'rating',
        'total_reviews',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'gallery' => 'array',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2',
        'package_price' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_free_trial' => 'boolean',
        'is_featured' => 'boolean',
        'is_online' => 'boolean',
    ];

    // Auto generate slug
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($class) {
            if (empty($class->slug)) {
                $class->slug = Str::slug($class->name);
            }
        });
    }

    // Relationships
    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'class_id');
    }

    public function activeBookings()
    {
        return $this->hasMany(Booking::class, 'class_id')
            ->whereIn('status', ['pending', 'confirmed']);
    }

    public function members()
    {
        return $this->belongsToMany(Member::class, 'bookings', 'class_id', 'member_id')
            ->withPivot('booking_date', 'status')
            ->withTimestamps();
    }

    // Helper Methods
    public function availableSlots()
    {
        $booked = $this->activeBookings()->count();
        return max(0, $this->max_participants - $booked);
    }

    public function isFull()
    {
        return $this->availableSlots() <= 0;
    }

    public function canBook()
    {
        return $this->status === 'active' && 
               !$this->isFull() &&
               ($this->end_date === null || $this->end_date >= now());
    }

    public function getImageUrlAttribute()
    {
        if (! $this->image) {
            return asset('images/default-class.jpg');
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }

    public function updateCurrentParticipants()
    {
        $this->current_participants = $this->activeBookings()->count();
        $this->save();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByTrainer($query, $trainerId)
    {
        return $query->where('trainer_id', $trainerId);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')
            ->whereRaw('current_participants < max_participants')
            ->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            });
    }
}