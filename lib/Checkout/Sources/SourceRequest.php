<?php

namespace Checkout\Sources;

use Checkout\Common\CustomerRequest;
use Checkout\Common\Phone;

abstract class SourceRequest
{
    public string $type;

    public string $reference;

    public Phone $phone;

    public CustomerRequest $customer;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

}
