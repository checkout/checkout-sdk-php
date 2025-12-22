<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Common;

class PaymentMethodOptions
{
    /**
     * @var PaymentMethodOption
     */
    public $sdk;

    /**
     * @var PaymentMethodOption
     */
    public $pay_in_full;

    /**
     * @var PaymentMethodOption
     */
    public $installments;

    /**
     * @var PaymentMethodOption
     */
    public $pay_now;
}
