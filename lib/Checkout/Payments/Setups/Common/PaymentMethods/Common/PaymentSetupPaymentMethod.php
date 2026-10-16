<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Common;

/**
 * The properties shared by every Payment Setup payment method (PaymentSetupPaymentMethod).
 *
 * Payment methods that also support initialization extend PaymentMethodBase instead.
 */
abstract class PaymentSetupPaymentMethod
{
    /**
     * The payment method status.
     * [Optional]
     * readOnly
     * Enum: "unavailable" "action_required" "ready" "initialization_required" "invalid"
     * @var string value of PaymentMethodStatus
     */
    public $status;

    /**
     * The list of error codes or indicators that highlight missing or invalid information.
     * [Optional]
     * readOnly
     * @var string[]
     */
    public $flags;
}
