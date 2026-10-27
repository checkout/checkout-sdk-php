<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Stablecoin;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodBase;

/**
 * The Stablecoin payment method's details and configuration.
 *
 * Inherits status and flags from PaymentSetupPaymentMethod. Extends PaymentMethodBase for compatibility;
 * the inherited initialization property is redeclared here as deprecated because this schema does not
 * define it.
 */
class Stablecoin extends PaymentMethodBase
{
    /**
     * The initialization state of the payment method. Redeclared from PaymentMethodBase only to mark it
     * deprecated for this payment method.
     * [Optional]
     * @deprecated Not part of the Stablecoin schema in the Payment Setups API specification; the API
     *     ignores it on requests. There is no replacement. Will be removed in the next major version.
     * @var string value of PaymentMethodInitialization
     */
    public $initialization;
}
