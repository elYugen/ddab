<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyDocument extends Model
{
    protected $table = "company_documents";
    
    protected $fillable = [
        'company_id',
        'name',
        'file_path',
        'document_type',
        'notes',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
