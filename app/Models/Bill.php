<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'total_amount',      // automatic sum of order items
        'balance',
        'payment_status',    // added
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'adjusted_total' => 'decimal:2',
        'balance' => 'decimal:2',
    ];
    
    // Payment status constants
    const STATUS_UNPAID = 'unpaid';
    const STATUS_PARTIALLY_PAID = 'partially_paid';
    const STATUS_PAID = 'paid';

    // Relationship: Bill belongs to an Order
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Relationship: Bill has many Payments
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // The authoritative total amount to use (manual override if exists)
    public function getFinalTotalAttribute(){
        return $this->adjusted_total ?? $this->total_amount;
    }

    public function applyPayment($amount){
        $newBalance = $this->balance - $amount;
        
        if ($newBalance < 0){
            throw new \Exception("Payment exceeds the invoice balance");
        }
        
        $this->balance = $newBalance;
        $this->updatePaymentStatus();
        $this->save();
    }

    // Update payment status based on balance
    protected function updatePaymentStatus(){
        $finalTotal = $this->getFinalTotalAttribute();
        
        if ($this->balance == 0) {
            $this->payment_status = self::STATUS_PAID;
        } elseif ($this->balance < $finalTotal) {
            $this->payment_status = self::STATUS_PARTIALLY_PAID;
        } else {
            $this->payment_status = self::STATUS_UNPAID;
        }
    }

    protected static function boot(){
        parent::boot();

        static::creating(function($bill){
            // Only set balance if not manually set
            if (is_null($bill->balance)) {
                $bill->balance = $bill->total_amount;
            }
            
            // Set initial payment status
            if (is_null($bill->payment_status)) {
                $bill->payment_status = self::STATUS_UNPAID;
            }
        });
    }
}