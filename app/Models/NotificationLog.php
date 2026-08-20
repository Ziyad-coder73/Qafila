<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $fillable = [
        'policy_id',
        'customer_name',
        'contact',
        'policy_number',
        'insurance_type',
        'notification_type',
        'reminder_stage',
        'channel',
        'status',
        'message',
        'sent_by',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function policy()
    {
        return $this->belongsTo(Policy::class);
    }

    public function sentBy()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
