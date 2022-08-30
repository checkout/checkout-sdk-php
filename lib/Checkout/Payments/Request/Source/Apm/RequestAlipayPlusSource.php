<?php

namespace Checkout\Payments\Request\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Request\Source\AbstractRequestSource;

class RequestAlipayPlusSource extends AbstractRequestSource
{
    public static function requestAlipayPlusSource()
    {
        return new RequestAlipayPlusSource(PaymentSourceType::$alipay_plus);
    }

    public static function requestAlipayPlusCNSource()
    {
        return new RequestAlipayPlusSource(PaymentSourceType::$alipay_cn);
    }

    public static function requestAlipayPlusGCashSource()
    {
        return new RequestAlipayPlusSource(PaymentSourceType::$gcash);
    }

    public static function requestAlipayPlusHKSource()
    {
        return new RequestAlipayPlusSource(PaymentSourceType::$alipay_hk);
    }

    public static function requestAlipayPlusDanaSource()
    {
        return new RequestAlipayPlusSource(PaymentSourceType::$dana);
    }

    public static function requestAlipayPlusKakaoPaySource()
    {
        return new RequestAlipayPlusSource(PaymentSourceType::$kakaopay);
    }

    public static function requestAlipayPlusTrueMoneySource()
    {
        return new RequestAlipayPlusSource(PaymentSourceType::$truemoney);
    }

    public static function requestAlipayPlusTNGSource()
    {
        return new RequestAlipayPlusSource(PaymentSourceType::$tng);
    }

}
