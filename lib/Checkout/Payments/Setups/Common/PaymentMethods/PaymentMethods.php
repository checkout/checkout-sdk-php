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
     * The Klarna payment method's details and configuration.
     * [Optional]
     * @var Klarna
     */
    public $klarna;

    /**
     * The stc pay payment method's details and configuration.
     * [Optional]
     * @var Stcpay
     */
    public $stcpay;

    /**
     * The Tabby payment method's details and configuration.
     * [Optional]
     * @var Tabby
     */
    public $tabby;

    /**
     * The Bizum payment method's details and configuration.
     * [Optional]
     * @var Bizum
     */
    public $bizum;

    /**
     * The Bacs payment method's details and configuration.
     * [Optional]
     * @var Bacs
     */
    public $bacs;

    /**
     * The Card Present payment method's details and configuration.
     * [Optional]
     * @var CardPresent
     */
    public $card_present;

    /**
     * The Pay by Bank (Open Banking) payment method's details and configuration.
     * [Optional]
     * @var PayByBank
     */
    public $pay_by_bank;

    /**
     * The Stablecoin payment method's details and configuration.
     * [Optional]
     * @var Stablecoin
     */
    public $stablecoin;
}
