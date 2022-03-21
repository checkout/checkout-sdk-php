<?php

namespace Checkout\Risk\Source;

use Checkout\Common\PaymentSourceType;

class CardSourcePrism extends RiskPaymentRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$card);
    }

    public $number;

    public $expiry_month;

    public $expiry_year;

    public $name;

    // Address
    public $billing_address;

    // Phone
    public $phone;

}
