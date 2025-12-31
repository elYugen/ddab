<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disinfection extends Model
{
    protected $table = "disinfections";

    protected $fillable = [
        'company_id',
        'user_id',
        'vehicle_id',
        'disinfected_at',
        'type',
        'protocol_reference',
        'product_used',
        'remarks'
    ];

    protected $casts = [
        'disinfected_at' => 'datetime',
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
