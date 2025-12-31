<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockItems extends Model
{
    protected $table = "stock_items";

    protected $fillable = [
        'company_id',
        'name',
        'picture_file_path',
        'quantity',
        'unit',
        'reorder_threshold'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'reorder_threshold' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
