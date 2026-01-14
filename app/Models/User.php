<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationship với Member
    public function member()
    {
        return $this->hasOne(\App\Models\Member::class, 'user_id');
    }

    // Auto create member when user is created
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            // Tự động tạo member khi user được tạo
            \App\Models\Member::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => '',
                'membership_type' => 'basic',
                'membership_start' => now(),
                'membership_end' => now()->addMonth(1),
                'membership_price' => 0,
                'status' => 'active',
            ]);
        });
    }
}