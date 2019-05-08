<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const PENDING = 'pending';
    public const PROCESSING = 'processing';
    public const PAYMENT_FAILED = 'payment_failed';
    public const COMPLETED = 'completed';
    
    protected $fillable = [
        'status',
        'address_id',
        'shipping_method_id',
        'subtotal',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Order $order) {
            $order->status = self::PENDING;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }
}
