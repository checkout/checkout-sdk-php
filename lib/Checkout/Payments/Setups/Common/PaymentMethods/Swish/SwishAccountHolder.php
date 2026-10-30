<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Swish;

use Checkout\Common\EmptyAsObject;

/**
 * The Swish account holder's details.
 */
#[EmptyAsObject]
class SwishAccountHolder
{
    /**
     * The account holder's first name.
     * [Optional]
     * min 1 characters
     * @var string
     */
    public $first_name;

    /**
     * The account holder's last name.
     * [Optional]
     * min 1 characters
     * @var string
     */
    public $last_name;
}
