<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
 
class Announcement extends Model {
    protected $fillable = ['title','message','type','target','is_active','starts_at','ends_at'];
    protected $casts    = ['is_active'=>'boolean','starts_at'=>'datetime','ends_at'=>'datetime'];
 
    // Active announcements for user
    public static function activeFor(string $userType = 'all') {
        return static::where('is_active', true)
            ->where(fn($q) => $q->where('target','all')->orWhere('target', $userType))
            ->where(fn($q) => $q->whereNull('starts_at')->orWhere('starts_at','<=',now()))
            ->where(fn($q) => $q->whereNull('ends_at')->orWhere('ends_at','>=',now()))
            ->latest()->get();
    }
}