<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    //
     protected $fillable = [
        'picture',
        'product_name',
        'category',
        'price',
        'unit',
        'min_threshold',
        'available_stock'
    ];

    // If you want price always cast to decimal when using the model
    protected $casts = [
        'price' => 'decimal:2',
    ];
  

    // Relationships (optional, depending on your app)
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relationship to Cart ← ADD THIS
    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function getImageAttribute()
    {
        if ($this->picture) {
            return asset($this->picture);
        }
        return null;
    }
    
public function getStockStatus()
{
    if ($this->available_stock <= 0) {
        return 'out-of-Stock';
    }

    if ($this->available_stock <= $this->min_threshold) {
        return 'low-stock';
    }

    return 'available';
}

    
}
