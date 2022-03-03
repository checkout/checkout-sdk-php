<?php

namespace Checkout\Payments\Source\Apm;

use Checkout\Common\Address;
use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Source\AbstractRequestSource;

class RequestKlarnaSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$klarna);
    }

    public string $authorization_token;

    public string $locale;

    public string $purchase_country;

    public int $tax_amount;

    public Address $billing_address;

    public KlarnaCustomer $customer;

    // KlarnaProduct
    public array $products;
}
