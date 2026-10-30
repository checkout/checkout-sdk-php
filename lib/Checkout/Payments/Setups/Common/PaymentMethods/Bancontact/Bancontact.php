<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Bancontact;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentSetupPaymentMethod;

/**
 * The Bancontact payment method's details and configuration.
 *
 * Inherits status and flags from PaymentSetupPaymentMethod.
 */
class Bancontact extends PaymentSetupPaymentMethod
{
    /**
     * The account holder's name.
     * The specification marks this optional, but the API keeps the method invalid until it is sent.
     * [Optional]
     * min 3 characters, max 100 characters
     * @var string
     */
    public $account_holder_name;
}
