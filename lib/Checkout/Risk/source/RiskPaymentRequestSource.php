<?php

namespace Checkout\Risk\source;

abstract class RiskPaymentRequestSource
{
    public string $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }


}
