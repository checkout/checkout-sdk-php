<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Paypal;

/**
 * Where PayPal obtains the shipping information.
 */
class PaypalShippingPreference
{
    /**
     * Redacts the shipping address from the PayPal pages.
     * @var string
     */
    public static $no_shipping = "no_shipping";

    /**
     * Obtains the shipping address from the customer's PayPal account.
     * @var string
     */
    public static $get_from_file = "get_from_file";

    /**
     * Uses the shipping address provided by the merchant.
     * @var string
     */
    public static $set_provided_address = "set_provided_address";
}
