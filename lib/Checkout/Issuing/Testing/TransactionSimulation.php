<?php

namespace Checkout\Issuing\Testing;

use Checkout\Common\Currency;

class TransactionSimulation
{
    /**
     * @var string value of TransactionType
     */
    public $type;

    /**
     * @var int
     */
    public $amount;

    /**
     * @var string value of Currency
     */
    public $currency;
}
