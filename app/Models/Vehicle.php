<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $table = "vehicles";

    protected $fillable = [
        'company_id',
        'name',
        'type',
        'registration_number',
        'vin_number',
        'category',
        'in_service',
        'service_start_date',
        'service_end_date',
        'ars_agreement_number',
        'ars_agreement_start_date',
        'ars_agreement_start_date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function disinfections()
    {
        return $this->hasMany(Disinfection::class);
    }
}
