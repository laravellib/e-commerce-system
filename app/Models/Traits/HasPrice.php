<?php

namespace App\Models\Traits;

use App\Money\Money;

/**
 * @property int price
 * @property Money money
 */
trait HasPrice
{
    public function getMoneyAttribute(): Money
    {
        return new Money($this->price);
    }

    public function getPriceFormattedAttribute(): string
    {
        return $this->money->formatted();
    }
}
