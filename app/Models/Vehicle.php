<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

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
        'ars_agreement_end_date',
        'deleted'
    ];

    protected $casts = [
        'in_service' => 'boolean',
        'service_start_date' => 'date',
        'service_end_date' => 'date',
        'ars_agreement_start_date' => 'date',
        'ars_agreement_end_date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function disinfections()
    {
        return $this->hasMany(Disinfection::class);
    }

    public function transports()
    {
        return $this->hasMany(Transport::class);
    }

    public function logBooks()
    {
        return $this->hasMany(VehicleLogBook::class);
    }

}
