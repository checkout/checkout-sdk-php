<?php

namespace Checkout\Payments\Four\Destination;

class PaymentRequestDestination
{
    public $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

}
