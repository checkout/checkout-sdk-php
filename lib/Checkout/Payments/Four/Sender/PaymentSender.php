<?php

namespace Checkout\Payments\Four\Sender;

class PaymentSender
{
    /**
     * @var string value of PaymentSenderType
     */
    public $type;

    public function __construct($type)
    {
        $this->type = $type;
    }
}
