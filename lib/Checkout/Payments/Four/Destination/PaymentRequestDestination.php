<?php

namespace Checkout\Payments\Four\Destination;

class PaymentRequestDestination
{
    public string $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

}
