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
use Checkout\Payments\Setups\Common\PaymentMethods\CashApp\CashApp;
use Checkout\Payments\Setups\Common\PaymentMethods\Card\Card;
use Checkout\Payments\Setups\Common\PaymentMethods\ApplePay\ApplePay;
use Checkout\Payments\Setups\Common\PaymentMethods\GooglePay\GooglePay;
use Checkout\Payments\Setups\Common\PaymentMethods\Instrument\PaymentSetupInstrument;

class PaymentMethods
{
    /**
     * The payment method that automatically confirms the payment when it is ready, for example card.
     * [Optional]
     * @var string
     */
    public $auto_confirm;

    /**
     * The instrument payment method's details and configuration.
     * [Optional]
     * @var PaymentSetupInstrument
     */
    public $instrument;

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
     * readOnly
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
     * The Pay by Bank (Open Banking) payment method's details and configuration. Serialized under the key
     * "paybybank".
     * [Optional]
     * @var PayByBank
     */
    public $paybybank;

    /**
     * The Pay by Bank (Open Banking) payment method's details and configuration, serialized under the key
     * "pay_by_bank".
     * [Optional]
     * @var PayByBank
     * @deprecated The API does not recognise the "pay_by_bank" key and ignores it without returning an
     * error. Use $paybybank instead. This property will be removed in a future major release.
     */
    public $pay_by_bank;

    /**
     * The Stablecoin payment method's details and configuration.
     * [Optional]
     * @var Stablecoin
     */
    public $stablecoin;

    /**
     * The Cash App payment method's details and configuration. Serialized under the key "cashapp".
     * [Optional]
     * @var CashApp
     */
    public $cashapp;

    /**
     * The Google Pay payment method's details and configuration. Serialized under the key "googlepay".
     * [Optional]
     * @var GooglePay
     */
    public $googlepay;

    /**
     * The Apple Pay payment method's details and configuration. Serialized under the key "applepay".
     * [Optional]
     * @var ApplePay
     */
    public $applepay;

    /**
     * The Card payment method's details and configuration.
     * [Optional]
     * @var Card
     */
    public $card;
}
