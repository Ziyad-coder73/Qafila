<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherRedemption extends Model
{
    protected $fillable = [
        'loyalty_member_id',
        'brand_id',
        'voucher_package_id',
        'partner_id',
        'redeemed_at',
    ];

    protected $casts = [
        'redeemed_at' => 'datetime',
    ];

    public function loyaltyMember()
    {
        return $this->belongsTo(LoyaltyMember::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function voucherPackage()
    {
        return $this->belongsTo(VoucherPackage::class);
    }

    public function partner()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }
}
