<?php

namespace Checkout\Payments\Four\Sender;

class PaymentSender
{
    public string $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

}
