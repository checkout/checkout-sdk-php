<?php

namespace Checkout\Apm\Klarna;

use Checkout\Common\PaymentSourceType;

class OrderCaptureRequest
{
    public function __construct()
    {
        $this->type = PaymentSourceType::$klarna;
    }

    public string $type;

    public int $amount;

    public string $reference;

    public array $Metadata;

    public Klarna $klarna;

    public KlarnaShippingInfo $shipping_info;

    public int $shipping_delay;

}
