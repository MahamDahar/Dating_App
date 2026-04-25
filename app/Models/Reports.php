<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{
    // Point explicitly to your table
    protected $table = 'reports';

    // Fillable columns
    protected $fillable = [
        'reporter_id',
        'reported_id',
        'reason',
        'description',
        'status',
    ];
}