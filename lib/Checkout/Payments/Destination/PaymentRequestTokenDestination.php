<?php

namespace Checkout\Payments\Destination;

use Checkout\Payments\PaymentDestinationType;

class PaymentRequestTokenDestination extends PaymentRequestDestination
{
    public function __construct()
    {
        parent::__construct(PaymentDestinationType::$token);
    }

    public $id;

    public $first_name;

    public $last_name;

    // Address
    public $billing_address;

    // Phone
    public $phone;

}
