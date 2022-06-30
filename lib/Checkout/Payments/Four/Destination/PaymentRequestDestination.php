<?php

namespace Checkout\Payments\Four\Destination;

class PaymentRequestDestination
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
