<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceRequest extends Model
{
    protected $fillable = [
        'full_name',
        'phone',
        'civil_id',
        'insurance_type_id',
        'status',
    ];

    public function insuranceType()
    {
        return $this->belongsTo(InsuranceType::class);
    }
}
