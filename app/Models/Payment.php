<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id',
        'customer_id',
        'amount',
        'recorded_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Run this immediately after a payment is created
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            // Automatically set recorded_by to current authenticated user
            if (is_null($payment->recorded_by) && auth()->check()) {
                $payment->recorded_by = auth()->id();
            }
        });

        static::created(function ($payment) {
            $bill = $payment->bill;
            
            // Reduce balance
            $bill->applyPayment($payment->amount);
        });
    }
}