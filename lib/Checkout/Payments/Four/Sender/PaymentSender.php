<?php

namespace Checkout\Payments\Four\Sender;

class PaymentSender
{
    public $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

}
