<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleLogBook extends Model
{
    protected $table = 'vehicle_log_books';

    protected $fillable = [
        'company_id',
        'user_id',
        'vehicle_id',
        'date',
        'start_mileage',
        'end_mileage',
        'mission_type',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'start_mileage' => 'integer',
        'end_mileage' => 'integer',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
