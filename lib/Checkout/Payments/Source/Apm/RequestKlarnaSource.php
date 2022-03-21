<?php

namespace Checkout\Payments\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Source\AbstractRequestSource;

class RequestKlarnaSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$klarna);
    }

    public $authorization_token;

    public $locale;

    public $purchase_country;

    public $tax_amount;

    // Address
    public $billing_address;

    // KlarnaCustomer
    public $customer;

    // array KlarnaProduct
    public $products;
}
