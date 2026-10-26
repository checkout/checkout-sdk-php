<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Bizum;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodOptions;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentSetupPaymentMethod;

/**
 * The Bizum payment method's details and configuration.
 *
 * Inherits status and flags from PaymentSetupPaymentMethod.
 */
class Bizum extends PaymentSetupPaymentMethod
{
    /**
     * The initialization state of the payment method.
     * [Optional]
     * @deprecated Not part of the Bizum schema in the Payment Setups API specification; the API ignores
     *     it on requests. There is no replacement. Will be removed in the next major version.
     * @var string value of PaymentMethodInitialization
     */
    public $initialization;

    /**
     * The payment method options.
     * [Optional]
     * @deprecated Not part of the Bizum schema in the Payment Setups API specification; the API ignores
     *     it on requests. There is no replacement. Will be removed in the next major version.
     * @var PaymentMethodOptions
     */
    public $payment_method_options;
}
