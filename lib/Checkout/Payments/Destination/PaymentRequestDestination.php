<?php

namespace Checkout\Payments\Destination;

abstract class PaymentRequestDestination
{
    public $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

}
