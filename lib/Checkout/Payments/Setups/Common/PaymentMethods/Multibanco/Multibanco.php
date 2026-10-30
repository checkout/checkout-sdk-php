<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Multibanco;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentSetupPaymentMethod;

/**
 * The Multibanco payment method's details and configuration.
 *
 * Inherits status and flags from PaymentSetupPaymentMethod.
 */
class Multibanco extends PaymentSetupPaymentMethod
{
    /**
     * The account holder's name.
     * [Optional]
     * min 3 characters, max 100 characters
     * @var string
     */
    public $account_holder_name;
}
