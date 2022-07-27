<?php

namespace Checkout\Sources\Previous;

use Checkout\Common\CustomerRequest;
use Checkout\Common\Phone;

abstract class SourceRequest
{
    /**
     * @var string value of SourceType
     */
    public $type;

    /**
     * @var string
     */
    public $reference;

    /**
     * @var Phone
     */
    public $phone;

    /**
     * @var CustomerRequest
     */
    public $customer;

    public function __construct($type)
    {
        $this->type = $type;
    }
}
