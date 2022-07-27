<?php

namespace Checkout\Payments\Previous\Destination;

abstract class PaymentRequestDestination
{
    /**
     * @var string value of PaymentDestinationType
     */
    public $type;

    public function __construct($type)
    {
        $this->type = $type;
    }
}
