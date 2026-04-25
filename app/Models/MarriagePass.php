<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarriagePass extends Model
{
    protected $table = 'marriage_passes';
    protected $fillable = ['passer_id', 'passed_id'];

    public function passer()
    {
        return $this->belongsTo(User::class, 'passer_id');
    }

    public function passed()
    {
        return $this->belongsTo(User::class, 'passed_id');
    }
}

