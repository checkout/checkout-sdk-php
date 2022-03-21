<?php

namespace Checkout\Apm\Klarna;

class CreditSessionRequest
{
    public $purchase_country;

    public $currency;

    public $locale;

    public $amount;

    public $tax_amount;

    //KlarnaProduct
    public $products;
}
