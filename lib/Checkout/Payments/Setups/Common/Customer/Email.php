<?php

namespace Checkout\Payments\Setups\Common\Customer;

use Checkout\Common\EmptyAsObject;

#[EmptyAsObject]
class Email
{
    /**
     * @var string
     */
    public $address;

    /**
     * @var bool
     */
    public $verified;
}
