<?php

namespace Checkout\Sources;

use Checkout\Common\CustomerRequest;
use Checkout\Common\Phone;

abstract class SourceRequest
{
    /**
     * @var SourceType
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
