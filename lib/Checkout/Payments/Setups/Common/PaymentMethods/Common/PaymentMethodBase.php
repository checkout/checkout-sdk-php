<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Common;

abstract class PaymentMethodBase
{
    /**
     * The payment method status.
     * [Optional]
     * readOnly
     * Enum: "unavailable" "action_required" "ready" "initialization_required" "invalid"
     * @var string
     */
    public $status;

    /**
     * The list of error codes or indicators that highlight missing or invalid information.
     * [Optional]
     * readOnly
     * @var string[]
     */
    public $flags;

    /**
     * The initialization state of the payment method. When you create a Payment Setup, this defaults to
     * disabled.
     * [Optional]
     * Default: "disabled"
     * Enum: "disabled" "enabled"
     * @var string
     */
    public $initialization;
}
