<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon; // ← ADD THIS

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'role',
        'birthday',
        'gender',
        'looking_for',
        'marital_status',
        'city',
        'is_premium',
        'premium_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'  => 'datetime',
            'password'           => 'hashed',
            'is_premium'         => 'boolean',
            'premium_expires_at' => 'datetime',
        ];
    }

    public function isPremium(): bool
    {
        return $this->is_premium &&
               ($this->premium_expires_at === null ||
                Carbon::parse($this->premium_expires_at)->isFuture());
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }
}  // ← ONLY ONE closing brace