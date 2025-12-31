<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    protected $table = "user_documents";
    
    protected $fillable = [
        'company_id',
        'user_id',
        'name',
        'file_path',
        'document_type',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
