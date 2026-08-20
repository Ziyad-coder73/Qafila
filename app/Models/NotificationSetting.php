<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    protected $fillable = [
        'type',
        'is_enabled',
        'channel_whatsapp',
        'channel_sms',
        'channel_email',
        'subject',
        'body',
        'renewal_payment_link',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'channel_whatsapp' => 'boolean',
        'channel_sms' => 'boolean',
        'channel_email' => 'boolean',
    ];

    public function enabledChannels(): array
    {
        return array_filter([
            'whatsapp' => $this->channel_whatsapp,
            'sms' => $this->channel_sms,
            'email' => $this->channel_email,
        ]);
    }
}
