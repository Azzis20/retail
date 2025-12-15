<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'total_amount',      // automatic sum of order items
        'adjusted_total',    // optional manual adjustment by owner
        'balance',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'adjusted_total' => 'decimal:2',
        'balance' => 'decimal:2',
    ];
    

    // Relationship: Bill belongs to an Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relationship: Bill has many Payments
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // The authoritative total amount to use (manual override if exists)
    public function getFinalTotalAttribute(){
        return $this->adjusted_total ?? $this->total_amount;
    }

    public function applyPayment($amount){
        $newBalance = $this->balance - $amount;
        
        if ($newBalanceew <0){
            throw new \Exception("Payment exceeds the invoice balance");
        }
        $this->balance = $newBalance;
        $this->save();
    }
  protected static function boot(){
    parent::boot();

    static::creating(function($bill){
        // Only set balance if not manually set
        if (is_null($bill->balance)) {
            $bill->balance = $bill->total_amount;
        }
    });
}

}




  // Update balance after payment
    // public function updateBalance()
    // {
    //     $totalPaid = $this->payments()->sum('amount');
    //     $this->balance = $this->final_total - $totalPaid;
    //     if ($this->balance < 0) {
    //         $this->balance = 0;
    //     }
    //     $this->save();
    // }

    // Automatically update balance after creating a payment
    // protected static function booted()
    // {
    //     static::created(function ($bill) {
    //         $bill->balance = $bill->final_total;
    //         $bill->save();
    //     });
    // }