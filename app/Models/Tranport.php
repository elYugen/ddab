<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tranport extends Model
{
    protected $table = "transports";
    
    protected $fillable = [
        'company_id',
        'patient_id',
        'driver_id',
        'vehicle_id',
        'assistant_id',
        'transport_type',
        'pickup_address',
        'destination_address',
        'distance_km',
        'transport_date',
        'start_time',
        'end_time',
        'is_emergency',
    ];

    protected $casts = [
        'transport_date' => 'date',
        'distance_km' => 'decimal:2',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_emergency' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function assistant()
    {
        return $this->belongsTo(User::class, 'assistant_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
