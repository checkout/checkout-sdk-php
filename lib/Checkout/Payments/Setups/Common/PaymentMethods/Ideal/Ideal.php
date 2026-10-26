<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Ideal;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentSetupPaymentMethod;

/**
 * The iDEAL payment method's details and configuration.
 *
 * Inherits status and flags from PaymentSetupPaymentMethod.
 */
class Ideal extends PaymentSetupPaymentMethod
{
    /**
     * A description of the products or services being paid for. Do not include HTML tags or special
     * characters, as these are rejected by iDEAL.
     * [Optional]
     * max 35 characters
     * @var string
     */
    public $description;
}
