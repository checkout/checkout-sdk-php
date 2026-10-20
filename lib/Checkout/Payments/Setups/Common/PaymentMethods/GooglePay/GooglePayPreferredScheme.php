<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\GooglePay;

/**
 * The card schemes Google Pay accepts as the preferred scheme.
 */
class GooglePayPreferredScheme
{
    /** @var string */
    public static $visa = "visa";

    /** @var string */
    public static $mastercard = "mastercard";
}
