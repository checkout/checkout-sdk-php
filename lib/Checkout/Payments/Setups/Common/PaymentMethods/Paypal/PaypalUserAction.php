<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Paypal;

/**
 * The user action for the PayPal widget.
 */
class PaypalUserAction
{
    /**
     * The customer enters payment details and is immediately directed to finalize the payment.
     * @var string
     */
    public static $pay_now = "pay_now";

    /**
     * The customer enters payment details and is redirected back to the merchant's site to review and
     * finalize the payment.
     * @var string
     */
    public static $continue = "continue";
}
