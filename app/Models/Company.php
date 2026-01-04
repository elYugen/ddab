<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = "companies";

    protected $fillable = [
        'name',
        'siret',
        'address',
        'postal_code',
        'city',
        'phone',
        'email'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'company_users')
            ->withPivot(['role', 'is_active'])
            ->withTimestamps();
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function stockItems()
    {
        return $this->hasMany(StockItems::class);
    }

    public function transports()
    {
        return $this->hasMany(Transport::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}
