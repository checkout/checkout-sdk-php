<?php

namespace Checkout\Apm\Klarna;

use Checkout\Common\Country;
use Checkout\Common\Currency;

class CreditSessionRequest
{
    /**
     * @var Country
     */
    public $purchase_country;

    /**
     * @var Currency
     */
    public $currency;

    /**
     * @var string
     */
    public $locale;

    /**
     * @var int
     */
    public $amount;

    /**
     * @var int
     */
    public $tax_amount;

    /**
     * @var array of KlarnaProduct
     */
    public $products;
}
