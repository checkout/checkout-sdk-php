<?php

namespace Checkout\Risk\source;

use Checkout\Common\Address;
use Checkout\Common\PaymentSourceType;
use Checkout\Common\Phone;

class CardSourcePrism extends RiskPaymentRequestSource
{
    public string $number;

    public int $expiry_month;

    public int $expiry_year;

    public string $name;

    public Address $billing_address;

    public Phone $phone;

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$card);
    }
}
