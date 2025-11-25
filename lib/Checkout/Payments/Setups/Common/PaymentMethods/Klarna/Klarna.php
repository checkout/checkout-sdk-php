<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Klarna;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodBase;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodOptions;

class Klarna extends PaymentMethodBase
{

    /**
     * @var KlarnaAccountHolder
     */
    public $account_holder;

    /**
     * @var PaymentMethodOptions
     */
    public $payment_method_options;
}
