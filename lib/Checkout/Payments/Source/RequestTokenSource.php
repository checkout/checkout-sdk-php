<?php

namespace Checkout\Payments\Source;

use Checkout\Common\PaymentSourceType;

class RequestTokenSource extends AbstractRequestSource
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

