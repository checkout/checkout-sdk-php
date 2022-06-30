<?php

namespace Checkout\Payments\Four\Request\Source;

class PayoutRequestSource
{
    /**
     * @var string value of PayoutSourceType
     */
    public $type;

    /**
     * @var int
     */
    public $amount;

    public function __construct($type)
    {
        $this->type = $type;
    }
}
