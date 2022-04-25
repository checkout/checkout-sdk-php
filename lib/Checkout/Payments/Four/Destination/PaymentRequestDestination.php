<?php

namespace Checkout\Payments\Four\Destination;

use Checkout\Payments\PaymentDestinationType;

class PaymentRequestDestination
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
