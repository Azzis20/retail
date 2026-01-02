<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



class Cart extends Model
{
     use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_id',
        'quantity',

        
    ];

    // Relationship to Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relationship to Customer (User)
    public function customer():BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // Calculate line total
    public function getLineTotalAttribute()
    {
        return $this->quantity * $this->product->price;
    }
}
