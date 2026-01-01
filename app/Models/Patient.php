<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $table = "patients";

    protected $fillable = [
        'company_id',
        'first_name',
        'last_name',
        'birth_date',
        'social_security_number',
        'street',
        'postal_code',
        'city',
        'phone',
        'deleted'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'deleted' => 'boolean',
    ];

    protected $appends = ['full_address'];

    /**
     * Accesseur pour l'adresse complète formatée
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->street,
            $this->postal_code,
            $this->city
        ]);

        if (empty($parts)) {
            return '';
        }

        // Format: "14 rue machin, 12000 Ville"
        $address = $this->street ?? '';
        if ($this->postal_code || $this->city) {
            $address .= $address ? ', ' : '';
            $address .= trim(($this->postal_code ?? '') . ' ' . ($this->city ?? ''));
        }

        return $address;
    }


    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
