<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Payment extends Model
{
    protected $fillable = [
        'user_id', 'plan_id', 'transaction_id',
        'amount', 'currency', 'payment_method',
        'status', 'stripe_session_id',
        'stripe_payment_intent', 'notes', 'paid_at',
    ];

    protected $casts = [
        'amount'  => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    // Status badge color
    public function statusColor(): string
    {
        return match($this->status) {
            'paid'     => 'success',
            'pending'  => 'warning',
            'failed'   => 'danger',
            'refunded' => 'secondary',
            default    => 'secondary',
        };
    }

    // Status icon
    public function statusIcon(): string
    {
        return match($this->status) {
            'paid'     => '✅',
            'pending'  => '⏳',
            'failed'   => '❌',
            'refunded' => '↩️',
            default    => '❓',
        };
    }
}