<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarriageMatch extends Model
{
    protected $table    = 'marriage_matches';
    protected $fillable = ['user_one_id', 'user_two_id', 'matched_at'];

    protected $dates = ['matched_at'];

    public function userOne() { return $this->belongsTo(User::class, 'user_one_id'); }
    public function userTwo() { return $this->belongsTo(User::class, 'user_two_id'); }
}