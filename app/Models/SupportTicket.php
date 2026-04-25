<?php

// ── SupportTicket Model ──
// Save as: app/Models/SupportTicket.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $fillable = [
        'user_id', 'subject', 'message',
        'status', 'admin_reply', 'replied_at'
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isOpen()     { return $this->status === 'open'; }
    public function isResolved() { return $this->status === 'resolved'; }
}