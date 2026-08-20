<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    protected $fillable = [
        'customer_name',
        'birthday',
        'contact_number',
        'insurance_type_id',
        'insurance_company',
        'policy_number',
        'date_of_issue',
        'policy_start_date',
        'policy_expiry_date',
        'premium',
        'commission',
        'agent_name',
        'policy_document',
        'created_by',
    ];

    protected $casts = [
        'birthday' => 'date',
        'date_of_issue' => 'date',
        'policy_start_date' => 'date',
        'policy_expiry_date' => 'date',
        'premium' => 'decimal:3',
        'commission' => 'decimal:3',
    ];

    public function insuranceType()
    {
        return $this->belongsTo(InsuranceType::class);
    }

    public function payments()
    {
        return $this->hasMany(PolicyPayment::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function loyaltyMember()
    {
        return $this->hasOne(LoyaltyMember::class);
    }

    public function isExpired(): bool
    {
        return $this->policy_expiry_date->isPast();
    }

    public function scopeFilter($query, $filters)
    {
        return $query
            ->when($filters['search'] ?? null, function ($q, $term) {
                $like = '%'.$term.'%';
                $q->where(function ($q) use ($like) {
                    $q->where('customer_name', 'like', $like)
                        ->orWhere('policy_number', 'like', $like)
                        ->orWhere('contact_number', 'like', $like)
                        ->orWhere('agent_name', 'like', $like);
                });
            })
            ->when($filters['insurance_type_id'] ?? null, fn ($q, $value) => $q->where('insurance_type_id', $value))
            ->when($filters['date_from'] ?? null, fn ($q, $value) => $q->whereDate('policy_start_date', '>=', $value))
            ->when($filters['date_to'] ?? null, fn ($q, $value) => $q->whereDate('policy_start_date', '<=', $value));
    }
}
