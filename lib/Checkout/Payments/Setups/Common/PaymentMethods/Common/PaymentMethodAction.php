<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Common;

/**
 * The next available action for a Payment Setup payment method (response only). Shared by Klarna,
 * PayPal and stc pay; each payment method returns only the properties that apply to it.
 */
class PaymentMethodAction
{
    /**
     * The type of action.
     * - Klarna: "sdk"
     * - PayPal: "sdk"
     * - stc pay: "otp"
     * [Optional]
     * readOnly
     * Enum: "sdk" "otp"
     * @var string
     */
    public $type;

    /**
     * The client token for the Klarna SDK. Klarna only.
     * [Optional]
     * readOnly
     * @var string
     */
    public $client_token;

    /**
     * The session ID. Klarna only.
     * [Optional]
     * readOnly
     * @var string
     */
    public $session_id;

    /**
     * The PayPal order ID to use with the PayPal SDK. PayPal only.
     * [Optional]
     * readOnly
     * @var string
     */
    public $order_id;
}
