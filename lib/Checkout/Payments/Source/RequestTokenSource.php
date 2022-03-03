<?php

namespace Checkout\Payments\Source;

use Checkout\Common\Address;
use Checkout\Common\PaymentSourceType;
use Checkout\Common\Phone;

class RequestTokenSource extends AbstractRequestSource
{

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$token);
    }

    public string $token;

    public Address $billing_address;

    public Phone $phone;

}

