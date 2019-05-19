<?php

namespace App\Models;

use App\Money\Money;
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
        'payment_method_id',
        'subtotal',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Order $order) {
            $order->status = self::PENDING;
        });
    }

    public function getSubtotalAttribute()
    {
        return new Money($this->attributes['subtotal']);
    }

    public function total()
    {
        return $this->subtotal->add($this->shippingMethod->money);
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

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function products()
    {
        return $this->belongsToMany(ProductVariation::class, 'product_variation_order')
            ->withPivot(['quantity'])
            ->withTimestamps();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
