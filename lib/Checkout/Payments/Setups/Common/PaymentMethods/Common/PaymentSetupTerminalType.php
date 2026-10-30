<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Common;

/**
 * The client-side terminal type of a Payment Setup wallet payment method.
 *
 * The wire values are lowercase. Do not use Checkout\Payments\TerminalType, which serializes uppercase
 * values.
 */
class PaymentSetupTerminalType
{
    /**
     * A PC browser.
     * @var string
     */
    public static $web = "web";

    /**
     * A mobile browser.
     * @var string
     */
    public static $wap = "wap";

    /**
     * A mobile application.
     * @var string
     */
    public static $app = "app";
}
