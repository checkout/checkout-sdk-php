<?php

namespace Checkout\Payments\Source;

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
