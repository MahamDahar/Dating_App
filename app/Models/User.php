<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'role',
        'role_id',
        'birthday',
        'gender',
        'looking_for',
        'marital_status',
        'country',
        'latitude',
        'longitude',
        'location_updated_at',
        'is_premium',
        'premium_expires_at',
        'profile_visibility',
        'email_verification_otp',
        'email_verification_otp_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'  => 'datetime',
            'photo_verified'     => 'boolean',
            'photo_verified_at'  => 'datetime',
            'password'           => 'hashed',
            'latitude'           => 'decimal:7',
            'longitude'          => 'decimal:7',
            'location_updated_at'=> 'datetime',
            'is_premium'         => 'boolean',
            'premium_expires_at' => 'datetime',
            'email_verification_otp_expires_at' => 'datetime',
        ];
    }

    // ── Premium check ──
    public function isPremium(): bool
    {
        return $this->is_premium &&
               ($this->premium_expires_at === null ||
                Carbon::parse($this->premium_expires_at)->isFuture());
    }

    // ── Profile relationship ──
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function photoVerifications()
    {
        return $this->hasMany(PhotoVerification::class);
    }

    public function latestPhotoVerification()
    {
        return $this->hasOne(PhotoVerification::class)->latestOfMany();
    }

    /** Gallery uploads (main photo = is_main, then order). */
    public function profilePhotos()
    {
        return $this->hasMany(UserProfilePhoto::class, 'user_id')
            ->orderByDesc('is_main')
            ->orderBy('order');
    }

    /** URL for primary profile image, or null if none uploaded. */
    public function mainProfilePhotoUrl(): ?string
    {
        $photo = $this->relationLoaded('profilePhotos')
            ? $this->profilePhotos->first()
            : $this->profilePhotos()->first();

        if (! $photo || empty($photo->path)) {
            return null;
        }

        return asset('storage/'.ltrim($photo->path, '/'));
    }

    /**
     * Backwards-compatible virtual attribute: blades use `$user->profile_photo` but
     * images are stored on user_profile_photos, not a users.profile_photo column.
     */
    public function getProfilePhotoAttribute(): ?string
    {
        return $this->mainProfilePhotoUrl();
    }

    // ── Role relationship ──
    public function roleModel()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // ── Permission check ──
    public function hasPermission(string $permission): bool
    {
        // Old-style admin string — full access
        if ($this->role === 'admin') {
            return true;
        }

        if (!$this->roleModel) {
            return false;
        }

        // Super admin gets everything
        if ($this->roleModel->name === 'super_admin') {
            return true;
        }

        return $this->roleModel->hasPermission($permission);
    }

    // ── Role check ──
    public function hasRole(string $role): bool
    {
        return $this->roleModel?->name === $role;
    }

    // ── Privacy Settings ──
    public function privacySettings()
    {
        return $this->hasOne(PrivacySetting::class);
    }

    public function getPrivacy()
    {
        return $this->privacySettings ?? new PrivacySetting([
            'who_can_send_proposals'  => 'everyone',
            'who_can_see_photos'      => 'everyone',
            'who_can_see_online'      => 'everyone',
            'who_can_see_last_active' => 'everyone',
            'who_can_message'         => 'everyone',
            'show_on_search_engines'  => true,
        ]);
    }

    // ── Likes ──
    public function likesGiven()
    {
        return $this->hasMany(Like::class, 'liker_id');
    }

    public function likesReceived()
    {
        return $this->hasMany(Like::class, 'liked_id');
    }

    // ── Proposals ──
    public function proposalsSent()
    {
        return $this->hasMany(Proposal::class, 'sender_id');
    }

    public function proposalsReceived()
    {
        return $this->hasMany(Proposal::class, 'receiver_id');
    }

    public function chatRequestsSent()
    {
        return $this->hasMany(ChatRequest::class, 'sender_id');
    }

    public function chatRequestsReceived()
    {
        return $this->hasMany(ChatRequest::class, 'receiver_id');
    }

    // ── Reports ──
    public function reportsReceived()
    {
        return $this->hasMany(Reports::class, 'reported_id');
    }

    public function reportsMade()
    {
        return $this->hasMany(Reports::class, 'reporter_id');
    }

    // ── Privacy helper ──
    public function isBlocked(): bool
    {
        return $this->is_blocked ?? false;
    }
}