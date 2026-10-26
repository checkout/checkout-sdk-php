<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Swish;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentSetupPaymentMethod;

/**
 * The Swish payment method's details and configuration.
 *
 * Inherits status and flags from PaymentSetupPaymentMethod.
 */
class Swish extends PaymentSetupPaymentMethod
{
    /**
     * A description that appears on the customer's billing statement.
     * [Optional]
     * @var string
     */
    public $billing_descriptor;

    /**
     * The account holder's details.
     * [Optional]
     * @var SwishAccountHolder
     */
    public $account_holder;
}
