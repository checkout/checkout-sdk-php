<?php

namespace Checkout\Issuing\Disputes\Entities;

use Checkout\Common\Currency;

class DisputedAmount
{
    /**
     * The amount is provided in the minor currency unit.
     *
     * @var int
     */
    public $amount;

    /**
     * The issuing currency, as a three-letter ISO currency code.
     *
     * @var string value of Currency
     */
    public $currency;
}
