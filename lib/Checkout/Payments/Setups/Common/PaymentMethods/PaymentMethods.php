<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods;

use Checkout\Payments\Setups\Common\PaymentMethods\Klarna\Klarna;
use Checkout\Payments\Setups\Common\PaymentMethods\Stcpay\Stcpay;
use Checkout\Payments\Setups\Common\PaymentMethods\Tabby\Tabby;
use Checkout\Payments\Setups\Common\PaymentMethods\Bizum\Bizum;
use Checkout\Payments\Setups\Common\PaymentMethods\Bacs\Bacs;
use Checkout\Payments\Setups\Common\PaymentMethods\CardPresent\CardPresent;
use Checkout\Payments\Setups\Common\PaymentMethods\PayByBank\PayByBank;
use Checkout\Payments\Setups\Common\PaymentMethods\Stablecoin\Stablecoin;

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

    /**
     * @var Bacs
     */
    public $bacs;

    /**
     * @var CardPresent
     */
    public $card_present;

    /**
     * @var PayByBank
     */
    public $pay_by_bank;

    /**
     * @var Stablecoin
     */
    public $stablecoin;
}
