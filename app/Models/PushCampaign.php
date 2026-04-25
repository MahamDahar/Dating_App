<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
 
class PushCampaign extends Model {
    protected $fillable = ['title','message','target','status','sent_count','sent_at'];
    protected $casts    = ['sent_at' => 'datetime'];
}