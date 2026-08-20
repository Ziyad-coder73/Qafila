<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyPackage extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'benefits',
        'discount_percentage',
    ];
}
