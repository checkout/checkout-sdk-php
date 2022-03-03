<?php

namespace Checkout\Payments\Four\Request\Source;

class PayoutRequestSource
{
    public string $type;

    public int $amount;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

}
