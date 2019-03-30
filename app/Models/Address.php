<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property bool default
 * @property User user
 */
class Address extends Model
{
    protected $fillable = [
        'name',
        'address_1',
        'city',
        'postal_code',
        'country_id',
        'default'
    ];

    protected $casts = [
        'default' => 'bool',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Address $address) {
            if ($address->default) {
                $address->user->addresses()->update([
                    'default' => false,
                ]);
            }
        });
    }

    public function setDefaultAttribute($value): void
    {
        $this->attributes['default'] = (bool) $value;
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
