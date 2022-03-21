<?php

namespace Checkout\Payments\Four\Request\Source;

class AbstractRequestSource
{
    public $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

}
