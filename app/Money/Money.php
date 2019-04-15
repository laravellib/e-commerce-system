<?php

namespace App\Money;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money as BaseMoney;
use NumberFormatter;

class Money
{
    private $money;

    public function __construct(int $price)
    {
        $this->money = new BaseMoney($price, new Currency('USD'));
    }

    public function amount()
    {
        return $this->money->getAmount();
    }

    public function formatted()
    {
        $formatter = new IntlMoneyFormatter(
            new NumberFormatter('en', NumberFormatter::CURRENCY), new ISOCurrencies()
        );

        return $formatter->format($this->money);
    }

    public function equals(Money $money)
    {
        return $this->money->equals($money->money);
    }

    public function add(Money $money)
    {
        $this->money = $this->money->add($money->money);

        return $this;
    }
}
