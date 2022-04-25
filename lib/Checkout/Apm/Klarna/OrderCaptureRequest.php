<?php

namespace Checkout\Apm\Klarna;

use Checkout\Common\PaymentSourceType;

class OrderCaptureRequest
{
    public function __construct()
    {
        $this->type = PaymentSourceType::$klarna;
    }

    /**
     * @var PaymentSourceType
     */
    public $type;

    /**
     * @var int
     */
    public $amount;

    /**
     * @var int
     */
    public $reference;

    /**
     * @var array
     */
    public $metadata;

    /**
     * @var Klarna
     */
    public $klarna;

    /**
     * @var KlarnaShippingInfo
     */
    public $shipping_info;

    /**
     * @var int
     */
    public $shipping_delay;
}
