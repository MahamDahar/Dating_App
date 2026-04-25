<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model {
    protected $fillable = [
        'question', 'answer', 'category', 'order', 'is_active'
    ];

    // Only active FAQs scope
    public function scopeActive($query) {
        return $query->where('is_active', true);
    }
}
