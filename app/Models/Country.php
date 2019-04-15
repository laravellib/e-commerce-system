<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'code',
        'name'
    ];

    public $timestamps = false;

    public function shippingMethods()
    {
        return $this->belongsToMany(ShippingMethod::class);
    }
}
