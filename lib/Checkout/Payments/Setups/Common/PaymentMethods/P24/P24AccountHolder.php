<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\P24;

/**
 * The P24 account holder's details.
 */
class P24AccountHolder
{
    /**
     * The account holder's name.
     * [Optional]
     * min 3 characters, max 100 characters
     * @var string
     */
    public $name;

    /**
     * The account holder's email address.
     * [Optional]
     * Format: email
     * max 254 characters
     * @var string
     */
    public $email;
}
