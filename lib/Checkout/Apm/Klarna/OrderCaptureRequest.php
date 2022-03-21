<?php

namespace Checkout\Apm\Klarna;

use Checkout\Common\PaymentSourceType;

class OrderCaptureRequest
{
    public function __construct()
    {
        $this->type = PaymentSourceType::$klarna;
    }

    public $type;

    public $amount;

    public $reference;

    public $metadata;

    // Klarna
    public $klarna;

    // KlarnaShippingInfo
    public $shipping_info;

    public $shipping_delay;

}
