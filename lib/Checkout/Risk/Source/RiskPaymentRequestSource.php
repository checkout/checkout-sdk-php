<?php

namespace Checkout\Risk\Source;

abstract class RiskPaymentRequestSource
{
    /**
     * @var string value of PaymentSourceType
     */
    public $type;

    public function __construct($type)
    {
        $this->type = $type;
    }
}
