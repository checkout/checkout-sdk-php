<?php

namespace Checkout\Payments\Destination;

use Checkout\Payments\PaymentDestinationType;

class PaymentRequestIdDestination extends PaymentRequestDestination
{
    public function __construct()
    {
        parent::__construct(PaymentDestinationType::$id);
    }

    public string $id;

    public string $first_name;

    public string $last_name;

}
