<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalPage extends Model {
    protected $fillable = ['type', 'title', 'content', 'last_updated_at'];

    protected $casts = [
        'last_updated_at' => 'datetime',
    ];
}