<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods;

use Checkout\Payments\Setups\Common\PaymentMethods\Klarna\Klarna;
use Checkout\Payments\Setups\Common\PaymentMethods\Stcpay\Stcpay;
use Checkout\Payments\Setups\Common\PaymentMethods\Tabby\Tabby;
use Checkout\Payments\Setups\Common\PaymentMethods\Bizum\Bizum;

class PaymentMethods
{
    /**
     * @var Klarna
     */
    public $klarna;

    /**
     * @var Stcpay
     */
    public $stcpay;

    /**
     * @var Tabby
     */
    public $tabby;

    /**
     * @var Bizum
     */
    public $bizum;
}
