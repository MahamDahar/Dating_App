<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    protected $table = 'user_profiles';

    protected $fillable = [
        'user_id',
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
        'religion_practice',
        'born_muslim',
        'interests',
        'bio',
        'personality',
        'profile_completion',
    ];

    protected $casts = [
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
        return (int) round(($filled / count($fields)) * 100);
    }
}