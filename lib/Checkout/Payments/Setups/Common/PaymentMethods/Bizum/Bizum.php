<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Bizum;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodBase;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodOptions;

/**
 * The Bizum payment method's details and configuration.
 *
 * Inherits status and flags from PaymentSetupPaymentMethod. Extends PaymentMethodBase for compatibility;
 * the inherited initialization property is redeclared here as deprecated because this schema does not
 * define it.
 */
class Bizum extends PaymentMethodBase
{
    /**
     * The initialization state of the payment method. Redeclared from PaymentMethodBase only to mark it
     * deprecated for this payment method.
     * [Optional]
     * @deprecated Not part of the Bizum schema in the Payment Setups API specification. There is no
     *     replacement. Will be removed in the next major version.
     * @var string value of PaymentMethodInitialization
     */
    public $initialization;

    /**
     * The payment method options.
     * [Optional]
     * @deprecated Not part of the Bizum schema in the Payment Setups API specification. There is no
     *     replacement. Will be removed in the next major version.
     * @var PaymentMethodOptions
     */
    public $payment_method_options;
}
