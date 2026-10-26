<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Swish;

/**
 * The Swish account holder's details.
 */
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
