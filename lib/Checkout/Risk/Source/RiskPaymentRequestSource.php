<?php

namespace Checkout\Risk\Source;

abstract class RiskPaymentRequestSource
{
    public $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

}
