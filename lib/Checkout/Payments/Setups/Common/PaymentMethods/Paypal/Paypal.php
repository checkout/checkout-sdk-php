<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Paypal;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodAction;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodBase;

/**
 * The PayPal payment method's details and configuration. Serialized under the key "paypal".
 *
 * Inherits status and flags from PaymentSetupPaymentMethod and initialization from PaymentMethodBase.
 */
class Paypal extends PaymentMethodBase
{
    /**
     * The user action for the PayPal widget.
     * - pay_now: after the customer clicks the PayPal button, the customer is redirected to a page to
     *   enter payment details and immediately directed to finalize the payment.
     * - continue: after the customer clicks the PayPal button, the customer is redirected to a page to
     *   enter payment details and then redirected back to the merchant's site to review and finalize
     *   the payment.
     * [Optional]
     * Enum: "pay_now" "continue"
     * @var string value of PaypalUserAction
     */
    public $user_action;

    /**
     * The brand name to display in the PayPal checkout experience.
     * [Optional]
     * max 127 characters
     * @var string
     */
    public $brand_name;

    /**
     * Where to obtain the shipping information.
     * - no_shipping: redacts the shipping address from the PayPal pages.
     * - get_from_file: obtains the shipping address from the customer's PayPal account.
     * - set_provided_address: uses the shipping address provided by the merchant.
     * [Optional]
     * Enum: "no_shipping" "get_from_file" "set_provided_address"
     * @var string value of PaypalShippingPreference
     */
    public $shipping_preference;

    /**
     * The next available action for the payment method. For PayPal the type is "sdk" and order_id
     * holds the PayPal order ID to use with the PayPal SDK.
     * [Optional]
     * readOnly
     * @var PaymentMethodAction
     */
    public $action;
}
