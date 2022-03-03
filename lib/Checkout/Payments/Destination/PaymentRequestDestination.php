<?php

namespace Checkout\Payments\Destination;

abstract class PaymentRequestDestination
{
    public string $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

}
