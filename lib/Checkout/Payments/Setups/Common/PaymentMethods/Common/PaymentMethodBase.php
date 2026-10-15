<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Common;

/**
 * A Payment Setup payment method that also supports initialization (PaymentSetupPaymentMethod plus
 * PaymentMethodInitialization). Inherits status and flags from PaymentSetupPaymentMethod.
 */
abstract class PaymentMethodBase extends PaymentSetupPaymentMethod
{
    /**
     * The initialization state of the payment method. When you create a Payment Setup, this defaults to
     * disabled.
     * [Optional]
     * Default: "disabled"
     * Enum: "disabled" "enabled"
     * @var string value of PaymentMethodInitialization
     */
    public $initialization;
}
