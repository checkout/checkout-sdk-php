<?php

namespace Checkout\Payments\Destination;

use Checkout\Payments\PaymentDestinationType;

abstract class PaymentRequestDestination
{
    /**
     * @var PaymentDestinationType
     */
    public $type;

    public function __construct($type)
    {
        $this->type = $type;
    }
}
