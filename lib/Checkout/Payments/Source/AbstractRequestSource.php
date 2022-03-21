<?php

namespace Checkout\Payments\Source;

abstract class AbstractRequestSource
{
    public $type;

    public function __construct($type)
    {
        $this->type = $type;
    }
}
