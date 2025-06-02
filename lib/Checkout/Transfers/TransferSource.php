<?php

namespace Checkout\Transfers;

use Checkout\Common\Currency;

class TransferSource
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var int
     */
    public $amount;

    /**
     * @var string value of Currency
     */
    public $currency;
}
