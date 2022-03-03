<?php

namespace Checkout\Payments\Four\Destination;

use Checkout\Payments\PaymentDestinationType;

class PaymentRequestIdDestination extends PaymentRequestDestination
{
    public function __construct()
    {
        parent::__construct(PaymentDestinationType::$id);
    }

    public string $id;

}
