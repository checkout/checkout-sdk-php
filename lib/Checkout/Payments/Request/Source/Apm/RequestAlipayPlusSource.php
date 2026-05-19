<?php

namespace Checkout\Payments\Request\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Request\Source\AbstractRequestSource;

class RequestAlipayPlusSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$alipay_plus);
    }

    /**
     * @deprecated Use {@see RequestAlipayPlusSource} constructor directly: `new RequestAlipayPlusSource()`.
     *             Will be removed in the next major version.
     */
    public static function requestAlipayPlusSource()
    {
        return new RequestAlipayPlusSource();
    }

    /**
     * @deprecated Use {@see RequestAlipayCnSource} instead.
     *             Will be removed in the next major version.
     */
    public static function requestAlipayPlusCNSource()
    {
        $source = new RequestAlipayPlusSource();
        $source->type = PaymentSourceType::$alipay_cn;
        return $source;
    }

    /**
     * @deprecated Use {@see RequestGcashSource} instead.
     *             Will be removed in the next major version.
     */
    public static function requestAlipayPlusGCashSource()
    {
        $source = new RequestAlipayPlusSource();
        $source->type = PaymentSourceType::$gcash;
        return $source;
    }

    /**
     * @deprecated Use {@see RequestAlipayHkSource} instead.
     *             Will be removed in the next major version.
     */
    public static function requestAlipayPlusHKSource()
    {
        $source = new RequestAlipayPlusSource();
        $source->type = PaymentSourceType::$alipay_hk;
        return $source;
    }

    /**
     * @deprecated Use {@see RequestDanaSource} instead.
     *             Will be removed in the next major version.
     */
    public static function requestAlipayPlusDanaSource()
    {
        $source = new RequestAlipayPlusSource();
        $source->type = PaymentSourceType::$dana;
        return $source;
    }

    /**
     * @deprecated Use {@see RequestKakaopaySource} instead.
     *             Will be removed in the next major version.
     */
    public static function requestAlipayPlusKakaoPaySource()
    {
        $source = new RequestAlipayPlusSource();
        $source->type = PaymentSourceType::$kakaopay;
        return $source;
    }

    /**
     * @deprecated Use {@see RequestTruemoneySource} instead.
     *             Will be removed in the next major version.
     */
    public static function requestAlipayPlusTrueMoneySource()
    {
        $source = new RequestAlipayPlusSource();
        $source->type = PaymentSourceType::$truemoney;
        return $source;
    }

    /**
     * @deprecated Use {@see RequestTngSource} instead.
     *             Will be removed in the next major version.
     */
    public static function requestAlipayPlusTNGSource()
    {
        $source = new RequestAlipayPlusSource();
        $source->type = PaymentSourceType::$tng;
        return $source;
    }
}
