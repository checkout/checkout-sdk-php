<?php

namespace Checkout\Payments\Destination;

use Checkout\Payments\PaymentDestinationType;

class PaymentRequestIdDestination extends PaymentRequestDestination
{
    public function __construct()
    {
        parent::__construct(PaymentDestinationType::$id);
    }

    public $id;

    public $first_name;

    public $last_name;

}
