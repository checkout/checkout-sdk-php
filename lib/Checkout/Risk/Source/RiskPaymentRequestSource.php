<?php

namespace Checkout\Risk\Source;

use Checkout\Common\PaymentSourceType;

abstract class RiskPaymentRequestSource
{
    /**
     * @var PaymentSourceType
     */
    public $type;

    public function __construct($type)
    {
        $this->type = $type;
    }
}
