<?php

namespace Checkout\Payments\Four\Sender;

class PaymentSender
{
    /**
     * @var PaymentSenderType
     */
    public $type;

    public function __construct($type)
    {
        $this->type = $type;
    }
}
