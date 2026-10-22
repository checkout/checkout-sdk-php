<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Tabby;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodBase;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodOptions;

/**
 * The Tabby payment method's details and configuration.
 *
 * Inherits status and flags from PaymentSetupPaymentMethod and initialization from PaymentMethodBase.
 */
class Tabby extends PaymentMethodBase
{
    /**
     * The available payment types for Tabby (for example, installments).
     * [Optional]
     * @var string[]
     */
    public $payment_types;

    /**
     * The payment method options.
     * [Optional]
     * @deprecated Not part of the API spec; the API no longer sends it and ignores it on requests.
     *     There is no replacement. It will be removed in the next major version.
     * @var PaymentMethodOptions
     */
    public $payment_method_options;
}
