<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Stcpay;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodBase;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodOptions;

class Stcpay extends PaymentMethodBase
{

    /**
     * @var string
     */
    public $otp;

    /**
     * @var PaymentMethodOptions
     */
    public $payment_method_options;
}
