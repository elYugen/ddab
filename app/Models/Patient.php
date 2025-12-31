<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = "patient";

    protected $fillable = [
        'company_id',
        'first_name',
        'last_name',
        'birth_date',
        'social_security_number',
        'address',
        'phone'
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
