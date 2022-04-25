<?php

namespace Checkout\Payments\Four\Request\Source;

use Checkout\Common\PaymentSourceType;

class AbstractRequestSource
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
