<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Paypal;

/**
 * The next available action for the PayPal payment method (response only).
 */
class PaypalAction
{
    /**
     * The type of action.
     * [Optional]
     * readOnly
     * Enum: "sdk"
     * @var string value of PaypalActionType
     */
    public $type;

    /**
     * The PayPal order ID to use with the PayPal SDK.
     * [Optional]
     * readOnly
     * @var string
     */
    public $order_id;
}
