<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Paypal;

/**
 * The type of the PayPal payment method action.
 */
class PaypalActionType
{
    /**
     * Complete the payment with the PayPal SDK, using the order_id.
     * @var string
     */
    public static $sdk = "sdk";
}
