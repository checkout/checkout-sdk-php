<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Stcpay;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodAction;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodBase;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodOptions;

/**
 * The stc pay payment method's details and configuration.
 *
 * Inherits status and flags from PaymentSetupPaymentMethod and initialization from PaymentMethodBase.
 */
class Stcpay extends PaymentMethodBase
{
    /**
     * The one-time password (OTP) for stc pay.
     * [Optional]
     * writeOnly
     * @var string
     */
    public $otp;

    /**
     * The next available action for the payment method. For stc pay the type is "otp".
     * [Optional]
     * readOnly
     * @var PaymentMethodAction
     */
    public $action;

    /**
     * @var PaymentMethodOptions
     */
    public $payment_method_options;
}
