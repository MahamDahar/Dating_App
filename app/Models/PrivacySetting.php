<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivacySetting extends Model {
    protected $fillable = [
        'user_id',
        'who_can_send_proposals',
        'who_can_see_photos',
        'who_can_see_online',
        'who_can_see_last_active',
        'who_can_message',
        'show_on_search_engines',
    ];

    protected $casts = [
        'show_on_search_engines' => 'boolean',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}