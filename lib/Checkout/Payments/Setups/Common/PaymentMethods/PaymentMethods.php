<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods;

use Checkout\Payments\Setups\Common\PaymentMethods\Ach\Ach;
use Checkout\Payments\Setups\Common\PaymentMethods\AlipayCn\AlipayCn;
use Checkout\Payments\Setups\Common\PaymentMethods\AlipayHk\AlipayHk;
use Checkout\Payments\Setups\Common\PaymentMethods\Alma\Alma;
use Checkout\Payments\Setups\Common\PaymentMethods\ApplePay\ApplePay;
use Checkout\Payments\Setups\Common\PaymentMethods\Bacs\Bacs;
use Checkout\Payments\Setups\Common\PaymentMethods\Bancontact\Bancontact;
use Checkout\Payments\Setups\Common\PaymentMethods\Benefit\Benefit;
use Checkout\Payments\Setups\Common\PaymentMethods\Bizum\Bizum;
use Checkout\Payments\Setups\Common\PaymentMethods\Blik\Blik;
use Checkout\Payments\Setups\Common\PaymentMethods\CardPresent\CardPresent;
use Checkout\Payments\Setups\Common\PaymentMethods\Card\Card;
use Checkout\Payments\Setups\Common\PaymentMethods\CashApp\CashApp;
use Checkout\Payments\Setups\Common\PaymentMethods\Dana\Dana;
use Checkout\Payments\Setups\Common\PaymentMethods\Eps\Eps;
use Checkout\Payments\Setups\Common\PaymentMethods\GCash\GCash;
use Checkout\Payments\Setups\Common\PaymentMethods\GooglePay\GooglePay;
use Checkout\Payments\Setups\Common\PaymentMethods\Ideal\Ideal;
use Checkout\Payments\Setups\Common\PaymentMethods\Instrument\PaymentSetupInstrument;
use Checkout\Payments\Setups\Common\PaymentMethods\KakaoPay\KakaoPay;
use Checkout\Payments\Setups\Common\PaymentMethods\Klarna\Klarna;
use Checkout\Payments\Setups\Common\PaymentMethods\Knet\Knet;
use Checkout\Payments\Setups\Common\PaymentMethods\MbWay\MbWay;
use Checkout\Payments\Setups\Common\PaymentMethods\MobilePay\MobilePay;
use Checkout\Payments\Setups\Common\PaymentMethods\Multibanco\Multibanco;
use Checkout\Payments\Setups\Common\PaymentMethods\Octopus\Octopus;
use Checkout\Payments\Setups\Common\PaymentMethods\P24\P24;
use Checkout\Payments\Setups\Common\PaymentMethods\PayByBank\PayByBank;
use Checkout\Payments\Setups\Common\PaymentMethods\PayNow\PayNow;
use Checkout\Payments\Setups\Common\PaymentMethods\Paypal\Paypal;
use Checkout\Payments\Setups\Common\PaymentMethods\Qpay\Qpay;
use Checkout\Payments\Setups\Common\PaymentMethods\Sepa\Sepa;
use Checkout\Payments\Setups\Common\PaymentMethods\Sequra\Sequra;
use Checkout\Payments\Setups\Common\PaymentMethods\Stablecoin\Stablecoin;
use Checkout\Payments\Setups\Common\PaymentMethods\Stcpay\Stcpay;
use Checkout\Payments\Setups\Common\PaymentMethods\Swish\Swish;
use Checkout\Payments\Setups\Common\PaymentMethods\Tabby\Tabby;
use Checkout\Payments\Setups\Common\PaymentMethods\Tamara\Tamara;
use Checkout\Payments\Setups\Common\PaymentMethods\Tng\Tng;
use Checkout\Payments\Setups\Common\PaymentMethods\TrueMoney\TrueMoney;
use Checkout\Payments\Setups\Common\PaymentMethods\Twint\Twint;
use Checkout\Payments\Setups\Common\PaymentMethods\Vipps\Vipps;
use Checkout\Payments\Setups\Common\PaymentMethods\WeChatPay\WeChatPay;

/**
 * The payment methods that are enabled on your account and available for use.
 *
 * Each property name is the literal key sent to the API, for example "gcash", "alipay_cn" or "paybybank".
 */
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
     * The PayNow payment method's details and configuration.
     * [Optional]
     * readOnly
     * @var PayNow
     */
    public $paynow;

    /**
     * The QPay payment method's details and configuration.
     * [Optional]
     * @var Qpay
     */
    public $qpay;

    /**
     * The EPS payment method's details and configuration.
     * [Optional]
     * readOnly
     * @var Eps
     */
    public $eps;

    /**
     * The iDEAL payment method's details and configuration.
     * [Optional]
     * @var Ideal
     */
    public $ideal;

    /**
     * The KNET payment method's details and configuration.
     * [Optional]
     * @var Knet
     */
    public $knet;

    /**
     * The Bancontact payment method's details and configuration.
     * [Optional]
     * @var Bancontact
     */
    public $bancontact;

    /**
     * The Benefit payment method's details and configuration.
     * [Optional]
     * readOnly
     * @var Benefit
     */
    public $benefit;

    /**
     * The Blik payment method's details and configuration.
     * [Optional]
     * @var Blik
     */
    public $blik;

    /**
     * The Vipps payment method's details and configuration.
     * [Optional]
     * readOnly
     * @var Vipps
     */
    public $vipps;

    /**
     * The Twint payment method's details and configuration.
     * [Optional]
     * readOnly
     * @var Twint
     */
    public $twint;

    /**
     * The Alipay CN payment method's details and configuration.
     * [Optional]
     * @var AlipayCn
     */
    public $alipay_cn;

    /**
     * The Alipay HK payment method's details and configuration.
     * [Optional]
     * @var AlipayHk
     */
    public $alipay_hk;

    /**
     * The GCash payment method's details and configuration.
     * [Optional]
     * @var GCash
     */
    public $gcash;

    /**
     * The TNG payment method's details and configuration.
     * [Optional]
     * @var Tng
     */
    public $tng;

    /**
     * The Dana payment method's details and configuration.
     * [Optional]
     * @var Dana
     */
    public $dana;

    /**
     * The MobilePay payment method's details and configuration.
     * [Optional]
     * readOnly
     * @var MobilePay
     */
    public $mobilepay;

    /**
     * The Tamara payment method's details and configuration.
     * [Optional]
     * @var Tamara
     */
    public $tamara;

    /**
     * The MBWay payment method's details and configuration.
     * [Optional]
     * readOnly
     * @var MbWay
     */
    public $mbway;

    /**
     * The Multibanco payment method's details and configuration.
     * [Optional]
     * @var Multibanco
     */
    public $multibanco;

    /**
     * The WeChatPay payment method's details and configuration.
     * [Optional]
     * readOnly
     * @var WeChatPay
     */
    public $wechatpay;

    /**
     * The KakaoPay payment method's details and configuration.
     * [Optional]
     * @var KakaoPay
     */
    public $kakaopay;

    /**
     * The TrueMoney payment method's details and configuration.
     * [Optional]
     * @var TrueMoney
     */
    public $truemoney;

    /**
     * The Octopus payment method's details and configuration.
     * [Optional]
     * readOnly
     * @var Octopus
     */
    public $octopus;

    /**
     * The P24 (Przelewy24) payment method's details and configuration.
     * [Optional]
     * @var P24
     */
    public $p24;

    /**
     * The Alma payment method's details and configuration.
     * [Optional]
     * @var Alma
     */
    public $alma;

    /**
     * The Swish payment method's details and configuration.
     * [Optional]
     * @var Swish
     */
    public $swish;

    /**
     * The Sequra payment method's details and configuration.
     * [Optional]
     * @var Sequra
     */
    public $sequra;

    /**
     * The ACH payment method's details and configuration.
     * [Optional]
     * @var Ach
     */
    public $ach;

    /**
     * The SEPA payment method's details and configuration.
     * [Optional]
     * @var Sepa
     */
    public $sepa;

    /**
     * The Bacs payment method's details and configuration.
     * [Optional]
     * @var Bacs
     */
    public $bacs;

    /**
     * The Pay by Bank (Open Banking) payment method's details and configuration.
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
     * The PayPal payment method's details and configuration.
     * [Optional]
     * @var Paypal
     */
    public $paypal;

    /**
     * The Cash App payment method's details and configuration.
     * [Optional]
     * @var CashApp
     */
    public $cashapp;

    /**
     * The Google Pay payment method's details and configuration.
     * [Optional]
     * @var GooglePay
     */
    public $googlepay;

    /**
     * The Apple Pay payment method's details and configuration.
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

    /**
     * The Stablecoin payment method's details and configuration.
     * [Optional]
     * @var Stablecoin
     */
    public $stablecoin;

    /**
     * The Card Present payment method's details and configuration.
     * [Optional]
     * @deprecated card_present is no longer part of the Payment Setups API specification. There is no
     *     replacement. Will be removed in the next major version.
     * @var CardPresent
     */
    public $card_present;
}
