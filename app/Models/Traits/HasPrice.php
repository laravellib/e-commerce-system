<?php

namespace App\Models\Traits;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use NumberFormatter;

/**
 * @property int price
 * @property Money money
 */
trait HasPrice
{
    public function getMoneyAttribute(): Money
    {
        return new Money($this->price, new Currency('USD'));
    }

    public function getPriceFormattedAttribute(): string
    {
        $formatter = new IntlMoneyFormatter(
            new NumberFormatter('en', NumberFormatter::CURRENCY), new ISOCurrencies()
        );

        return $formatter->format($this->money);
    }
}
