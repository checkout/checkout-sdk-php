<?php

namespace Checkout\Payments\Destination;

use Checkout\Common\Address;
use Checkout\Common\Phone;
use Checkout\Payments\PaymentDestinationType;

class PaymentRequestTokenDestination extends PaymentRequestDestination
{
    public function __construct()
    {
        parent::__construct(PaymentDestinationType::$token);
    }

    public string $id;

    public string $first_name;

    public string $last_name;

    public Address $billing_address;

    public Phone $phone;

}
