<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Stablecoin;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentSetupPaymentMethod;

/**
 * The Stablecoin payment method's details and configuration.
 *
 * Inherits status and flags from PaymentSetupPaymentMethod.
 */
class Stablecoin extends PaymentSetupPaymentMethod
{
    /**
     * The initialization state of the payment method.
     * [Optional]
     * @deprecated Not part of the Stablecoin schema in the Payment Setups API specification; the API
     *     ignores it on requests. There is no replacement. Will be removed in the next major version.
     * @var string value of PaymentMethodInitialization
     */
    public $initialization;
}
