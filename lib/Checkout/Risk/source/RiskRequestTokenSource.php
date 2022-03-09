<?php

namespace Checkout\Risk\source;

use Checkout\Common\Address;
use Checkout\Common\PaymentSourceType;
use Checkout\Common\Phone;

class RiskRequestTokenSource extends RiskPaymentRequestSource
{

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$token);
    }

    public string $token;

    public Address $billing_address;

    public Phone $phone;

}

