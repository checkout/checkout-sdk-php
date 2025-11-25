<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Bizum;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodBase;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodOptions;

class Bizum extends PaymentMethodBase
{

    /**
     * @var PaymentMethodOptions
     */
    public $payment_method_options;
}
