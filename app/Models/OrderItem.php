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
        'line_total',  // ← ADD THIS
        'is_proceseed',

 
    ];
     protected $casts = [
         'is_proceseed' => 'boolean',
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];


    // Accessor for dynamic line_total
    public function getLineTotalAttribute($value)
    {
        // If line_total is stored, use it; otherwise calculate
        return $value ?? ($this->quantity * $this->unit_price);
    }

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
  

    protected static function booted()
    {
        static::creating(function ($item) {
            $item->line_total = $item->quantity * $item->unit_price;
        });

        static::updating(function ($item) {
            $item->line_total = $item->quantity * $item->unit_price;
        });
    }

}
