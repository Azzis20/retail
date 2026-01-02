<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Mass assignable fields
    protected $fillable = [
        'customer_id',
        'processed_by',
        'status',
        'notes',

        
    ];

    /**
     * Relationships
     */

    // The customer who placed the order
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // The staff/user who processed the order
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // The order items associated with this order
      public function orderItems()
    {
        return $this->hasMany(OrderItem::class); // Make sure OrderItem model exists
    }

    // The bill associated with this order
    public function bill()
    {
        return $this->hasOne(Bill::class);
    }

    /**
     * Convenience methods
     */

    // Calculate the total amount from order items
    public function calculateTotalAmount()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });
    }

    // Update total_amount in case you store it in the orders table
    public function updateTotalAmount()
    {
        $this->total_amount = $this->calculateTotalAmount();
        $this->save();
    }

    //return true all items are proceesedd perfect to enable your 'mark as ready to pick up' button
    public function isFullyProcessed()
    {
        return $this->items()->where('is_processed', false)->count() === 0;
    }

}


// Handling check box inyour ui

// $orderItem = OrderItem::find($id);
// $orderItem->is_processed = $request->input('is_processed');
// $orderItem->save();

