<?php

namespace Checkout\Risk\Source;

use Checkout\Common\PaymentSourceType;

class RiskRequestTokenSource extends RiskPaymentRequestSource
{

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$token);
    }

    public $token;

    // Address
    public $billing_address;

    // Phone
    public $phone;

}

