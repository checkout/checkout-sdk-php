<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\P24;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentSetupPaymentMethod;

/**
 * The P24 (Przelewy24) payment method's details and configuration.
 *
 * Inherits status and flags from PaymentSetupPaymentMethod.
 */
class P24 extends PaymentSetupPaymentMethod
{
    /**
     * The account holder's details.
     * [Optional]
     * @var P24AccountHolder
     */
    public $account_holder;
}
