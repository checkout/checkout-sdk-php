<?php

namespace Checkout\Apm\Previous\Klarna;

class CreditSessionRequest
{
    /**
     * @var string values of Country
     */
    public $purchase_country;

    /**
     * @var string value of Currency
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
