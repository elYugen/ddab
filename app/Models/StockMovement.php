<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $table = "stock_movements";

    protected $fillable = [
        'user_id',
        'company_id',
        'stock_item_id',
        'type',
        'quantity',
        'reason',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function stockItem()
    {
        return $this->belongsTo(StockItems::class);
    }
}
