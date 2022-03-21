<?php

namespace Checkout\Sources;

abstract class SourceRequest
{
    public $type;

    public $reference;

    // Phone
    public $phone;

    // CustomerRequest
    public $customer;

    public function __construct($type)
    {
        $this->type = $type;
    }

}
