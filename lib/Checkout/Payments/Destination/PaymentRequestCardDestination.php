<?php

namespace Checkout\Payments\Destination;

use Checkout\Common\Address;
use Checkout\Common\Phone;
use Checkout\Payments\PaymentDestinationType;

class PaymentRequestCardDestination extends PaymentRequestDestination
{

    public function __construct()
    {
        parent::__construct(PaymentDestinationType::$card);
    }

    public string $number;

    public int $expiry_month;

    public int $expiry_year;

    public string $first_name;

    public string $last_name;

    public string $name;

    public Address $billing_address;

    public Phone $phone;

}

