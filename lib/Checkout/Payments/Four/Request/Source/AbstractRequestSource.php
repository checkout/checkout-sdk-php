<?php

namespace Checkout\Payments\Four\Request\Source;

class AbstractRequestSource
{
    public string $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

}
