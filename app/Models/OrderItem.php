<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    //
    // use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        // 'line_total', // optional if you compute it dynamically
    ];
     protected $casts = [
        'is_processed' => 'boolean',
        // other casts...
    ];


    // Accessor for dynamic line_total
    public function getLineTotalAttribute()
    {
        return $this->quantity * $this->unit_price;
    }

    // Relationships
    public function order():BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

}
