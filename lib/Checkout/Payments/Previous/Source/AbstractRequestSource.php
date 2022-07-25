<?php

namespace Checkout\Payments\Previous\Source;

abstract class AbstractRequestSource
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
