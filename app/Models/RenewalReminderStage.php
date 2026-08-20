<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RenewalReminderStage extends Model
{
    protected $fillable = [
        'days_before',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];
}
