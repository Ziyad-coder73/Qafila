<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $attributes = [
        'is_active' => true,
    ];

    protected $fillable = [
        'name',
        'logo',
        'location',
        'contact_info',
        'owner_name',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function voucherPackages()
    {
        return $this->hasMany(VoucherPackage::class);
    }

    public function partners()
    {
        return $this->hasMany(User::class)->where('role', 'partner');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
