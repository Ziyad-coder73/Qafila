<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LoyaltyMember extends Model
{
    protected $fillable = [
        'policy_id',
        'membership_number',
        'card_token',
        'full_name',
        'phone',
        'id_number',
        'insurance_company',
        'insurance_type_id',
        'loyalty_package',
        'status',
        'card_issued_at',
        'expires_at',
        'issued_by',
        'delivery_method',
        'delivery_status',
    ];

    protected $casts = [
        'card_issued_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function policy()
    {
        return $this->belongsTo(Policy::class);
    }

    public function insuranceType()
    {
        return $this->belongsTo(InsuranceType::class);
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function redemptions()
    {
        return $this->hasMany(VoucherRedemption::class);
    }

    public function package()
    {
        return LoyaltyPackage::where('slug', $this->loyalty_package)->first();
    }

    public function isValid(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        return ! $this->expires_at || $this->expires_at->isFuture();
    }

    public function displayInsuranceCompany(): ?string
    {
        return $this->policy?->insurance_company ?? $this->insurance_company;
    }

    public function displayInsuranceType(): ?string
    {
        return $this->policy?->insuranceType?->name ?? $this->insuranceType?->name;
    }

    public function isManual(): bool
    {
        return $this->policy_id === null;
    }

    public static function generateMembershipNumber(): string
    {
        do {
            $number = 'QAF-'.strtoupper(Str::random(6));
        } while (static::where('membership_number', $number)->exists());

        return $number;
    }
}
