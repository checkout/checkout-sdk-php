<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Qpay;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentSetupPaymentMethod;

/**
 * The QPay payment method's details and configuration.
 *
 * Inherits status and flags from PaymentSetupPaymentMethod.
 */
class Qpay extends PaymentSetupPaymentMethod
{
    /**
     * The Qatari national ID. Must start with 2 or 3, followed by 10 digits.
     * [Optional]
     * writeOnly
     * @var string
     */
    public $national_id;

    /**
     * A description of the payment order. Alphanumeric characters only.
     * [Optional]
     * max 255 characters
     * @var string
     */
    public $description;
}
