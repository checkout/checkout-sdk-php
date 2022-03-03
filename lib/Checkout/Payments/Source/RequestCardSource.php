<?php

namespace Checkout\Payments\Source;

use Checkout\Common\Address;
use Checkout\Common\PaymentSourceType;
use Checkout\Common\Phone;

class RequestCardSource extends AbstractRequestSource
{

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$card);
    }

    public string $number;

    public int $expiry_month;

    public int $expiry_year;

    public string $name;

    public string $cvv;

    public bool $stored;

    public Address $billing_address;

    public Phone $phone;

}
