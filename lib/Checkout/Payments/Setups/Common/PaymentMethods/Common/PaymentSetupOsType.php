<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Common;

/**
 * The customer's operating system type for a Payment Setup wallet payment method.
 *
 * The wire values are lowercase. Do not use Checkout\Payments\OsType, which serializes uppercase values.
 */
class PaymentSetupOsType
{
    /** @var string */
    public static $android = "android";

    /** @var string */
    public static $ios = "ios";
}
