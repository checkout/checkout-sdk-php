<?php

namespace Checkout\Payments\Sessions;

/**
 * The type of payment method that can be enabled or disabled for a payment session.
 *
 * This is a consolidated list covering every payment method the Flow API accepts for
 * enabled_payment_methods / disabled_payment_methods. Constants marked @deprecated are no
 * longer part of the specification but are kept for backward compatibility.
 */
class PaymentMethodsType
{
    public $ALIPAY_CN = "alipay_cn";
    public $ALIPAY_HK = "alipay_hk";
    public $ALMA = "alma";
    public $APPLEPAY = "applepay";
    public $BANCONTACT = "bancontact";
    public $BENEFIT = "benefit";
    public $BIZUM = "bizum";
    public $CARD = "card";
    public $DANA = "dana";
    public $EPS = "eps";

    /**
     * @deprecated No longer part of the specification.
     */
    public $GIROPAY = "giropay";
    public $GCASH = "gcash";
    public $GOOGLEPAY = "googlepay";
    public $IDEAL = "ideal";
    public $KAKAOPAY = "kakaopay";
    public $KLARNA = "klarna";
    public $KNET = "knet";
    public $MBWAY = "mbway";
    public $MOBILEPAY = "mobilepay";
    public $MULTIBANCO = "multibanco";
    public $OCTOPUS = "octopus";
    public $PRZELEWY24 = "p24";
    public $PAYNOW = "paynow";
    public $PAYPAL = "paypal";
    public $PLAID = "plaid";
    public $QPAY = "qpay";

    /**
     * Fully supported by the API but deliberately unlisted in the public specification, so that
     * merchants do not disable Remember Me en masse. Do not remove it as an unspecified value.
     */
    public $REMEMBER_ME = "remember_me";
    public $SEPA = "sepa";

    /**
     * @deprecated No longer part of the specification.
     */
    public $SOFORT = "sofort";
    public $STCPAY = "stcpay";
    public $STORED_CARD = "stored_card";
    public $TABBY = "tabby";
    public $TAMARA = "tamara";
    public $TNG = "tng";
    public $TRUEMONEY = "truemoney";
    public $TWINT = "twint";
    public $VIPPS = "vipps";
    public $WECHATPAY = "wechatpay";
}
