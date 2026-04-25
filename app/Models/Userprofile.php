<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserProfile extends Model
{
    protected $table = 'user_profiles';

    protected $fillable = [
        'user_id',
        'date_of_birth',
        'city',
        'country',
        'sect',
        'profession',
        'education',
        'notifications_enabled',
        'hide_from_contacts',
        'nationality',
        'grew_up',
        'ethnicity',
        'height_cm',
        'marital_status',
        'marriage_intentions',
        'smoking',
        'drinking',
        'want_children',
        'num_children',
        'languages',
        'religion_practice',
        'born_muslim',
        'interests',
        'bio',
        'personality',
        'profile_completion',
    ];

    protected $casts = [
        'date_of_birth'          => 'date',
        'notifications_enabled' => 'boolean',
        'hide_from_contacts'    => 'boolean',
        'height_cm'             => 'integer',
        'profile_completion'    => 'integer',
    ];

    // -----------------------------------------------
    // Relationship
    // -----------------------------------------------
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(UserProfilePhoto::class, 'user_id', 'user_id')
            ->orderByDesc('is_main')
            ->orderBy('order');
    }

    // -----------------------------------------------
    // Accessors — return arrays for multi-value fields
    // -----------------------------------------------
    public function getInterestsArrayAttribute(): array
    {
        return $this->interests ? explode(',', $this->interests) : [];
    }

    public function getEthnicityArrayAttribute(): array
    {
        return $this->ethnicity ? explode(',', $this->ethnicity) : [];
    }

    public function getPersonalityArrayAttribute(): array
    {
        return $this->personality ? explode(',', $this->personality) : [];
    }

    // Height formatted as ft'in"
    public function getHeightFormattedAttribute(): ?string
    {
        if (!$this->height_cm) return null;
        $totalInches = $this->height_cm / 2.54;
        $feet   = (int) ($totalInches / 12);
        $inches = (int) round(fmod($totalInches, 12));
        return "{$feet}'{$inches}\"";
    }

    // -----------------------------------------------
    // Profile completion calculator
    // -----------------------------------------------
    public function calculateCompletion(): int
    {
        $fields = [
            'sect', 'profession', 'education', 'nationality',
            'grew_up', 'ethnicity', 'height_cm', 'marital_status',
            'marriage_intentions', 'religion_practice', 'born_muslim',
            'interests', 'bio', 'personality',
        ];

        $filled = collect($fields)->filter(fn($f) => !empty($this->$f))->count();

        // Profile photo is required for full completion.
        $hasPhoto = UserProfilePhoto::where('user_id', $this->user_id)->exists();

        $totalCriteria = count($fields) + 1; // +1 for photo
        $completed = $filled + ($hasPhoto ? 1 : 0);

        return (int) round(($completed / $totalCriteria) * 100);
    }
}