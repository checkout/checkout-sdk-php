<?php

namespace Checkout\Payments\Sender;

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
