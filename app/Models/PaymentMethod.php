<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'default',
        'card_type',
        'last_four',
        'provider_id',
    ];

    protected $casts = [
        'default' => 'bool',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (PaymentMethod $paymentMethod) {
            if ($paymentMethod->default) {
                $paymentMethod->user->paymentMethods()->update([
                    'default' => false,
                ]);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
