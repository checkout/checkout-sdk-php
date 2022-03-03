<?php

namespace Checkout\Payments\Four\Request\Source;

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
