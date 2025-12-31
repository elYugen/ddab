<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = "invoices";

    protected $fillable = [
        'company_id',
        'invoice_number',
        'patient_id',
        'subtotal',
        'tax_amount',
        'total_amount',
        'status',
        'issue_date',
        'due_date'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
    ];

    // scope factures impayées
    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', ['DRAFT', 'SENT', 'PARTIALLY_PAID']);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
