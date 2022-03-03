<?php

namespace Checkout\Payments\Four\Request\Source;

use Checkout\Common\Address;
use Checkout\Common\PaymentSourceType;
use Checkout\Common\Phone;

class RequestNetworkTokenSource extends AbstractRequestSource
{

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$network_token);
    }

    public string $token;

    public int $expiry_month;

    public int $expiry_year;

    public string $token_type;

    public string $cryptogram;

    public string $eci;

    public bool $stored;

    public string $name;

    public string $cvv;

    public Address $billing_address;

    public Phone $phone;

}
