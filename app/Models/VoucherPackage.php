<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherPackage extends Model
{
    protected $fillable = [
        'brand_id',
        'title',
        'description',
        'is_available',
        'sort_order',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
