<?php

namespace Checkout\Customers;

use Checkout\Common\Phone;

class CustomerRequest
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $name;

    /**
     * @var Phone
     */
    public $phone;

    /**
     * @var array
     */
    public $metadata;

    /**
     * Not available on previous
     * @var array of string
     */
    public $instruments;
}
