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
     * @var PaymentMethodOptions
     */
    public $payment_method_options;
}
