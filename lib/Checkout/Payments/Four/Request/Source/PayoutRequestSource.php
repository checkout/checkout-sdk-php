<?php

namespace Checkout\Payments\Four\Request\Source;

class PayoutRequestSource
{
    public $type;

    public $amount;

    public function __construct($type)
    {
        $this->type = $type;
    }

}
