<?php
// ── app/Models/EmailCampaign.php ──
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
 
class EmailCampaign extends Model {
    protected $fillable = ['title','subject','body','target','status','sent_count','sent_at'];
    protected $casts    = ['sent_at' => 'datetime'];
}