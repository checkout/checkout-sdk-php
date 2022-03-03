<?php

namespace Checkout\Apm\Klarna;

class CreditSessionRequest
{
    public string $purchase_country;

    public string $currency;

    public string $locale;

    public int $amount;

    public int $tax_amount;

    //KlarnaProduct
    public array $products;
}
