<?php

namespace Checkout\Payments\Destination;

use Checkout\Payments\PaymentDestinationType;

class PaymentRequestCardDestination extends PaymentRequestDestination
{

    public function __construct()
    {
        parent::__construct(PaymentDestinationType::$card);
    }

    public $number;

    public $expiry_month;

    public $expiry_year;

    public $first_name;

    public $last_name;

    public $name;

    // Address
    public $billing_address;

    // Phone
    public $phone;

}
