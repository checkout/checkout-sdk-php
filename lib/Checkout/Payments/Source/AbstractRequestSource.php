<?php

namespace Checkout\Payments\Source;

use Checkout\Common\PaymentSourceType;

abstract class AbstractRequestSource
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
