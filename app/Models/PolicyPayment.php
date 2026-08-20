<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PolicyPayment extends Model
{
    protected $fillable = [
        'policy_id',
        'payment_method',
        'payment_type',
        'amount',
        'document',
        'paid_at',
        'recorded_by',
    ];

    protected $casts = [
        'paid_at' => 'date',
        'amount' => 'decimal:3',
    ];

    public function policy()
    {
        return $this->belongsTo(Policy::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
