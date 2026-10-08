<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Common;

/**
 * The payment method status.
 */
class PaymentMethodStatus
{
    /** @var string */
    public static $unavailable = "unavailable";

    /** @var string */
    public static $action_required = "action_required";

    /** @var string */
    public static $ready = "ready";

    /** @var string */
    public static $initialization_required = "initialization_required";

    /** @var string */
    public static $invalid = "invalid";
}
