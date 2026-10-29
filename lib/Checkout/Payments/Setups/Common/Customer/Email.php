<?php

namespace Checkout\Payments\Setups\Common\Customer;

use Checkout\Common\EmptyAsObject;

/**
 * Details of the customer's email.
 */
#[EmptyAsObject]
class Email
{
    /**
     * The customer's email address.
     * [Optional]
     * @var string
     */
    public $address;

    /**
     * Specifies whether the customer's email address is verified.
     * [Optional]
     * @var bool
     */
    public $verified;
}
