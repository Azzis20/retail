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
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function bill():BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    public function customer():BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }


    //newly added

    //run this immediately  adter a payment is created
    protected static function boot(){
        parent::boot();

        static::created(function($payment){

            $bill = $payment->bill;

            //reduce balance
            $bill->applyPayment($payment->amount);        });
    }
}
