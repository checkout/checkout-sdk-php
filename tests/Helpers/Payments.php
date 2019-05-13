<?php

namespace Checkout\tests\Helpers;

use Checkout\Models\Payments\Action;
use Checkout\Models\Payments\Capture;
use Checkout\Models\Payments\CardSource;
use Checkout\Models\Payments\Payment;
use Checkout\Models\Payments\Refund;
use Checkout\Models\Payments\ThreeDs;
use Checkout\Models\Payments\Voids;

class Payments
{
    public static function generateID()
    {
        return 'pay_' . substr(md5(rand()), 0, 26);
    }

    public static function generateActionID()
    {
        return 'act_' . substr(md5(rand()), 0, 26);
    }

    public static function generateModel($currency = 'USD')
    {
        $payment = new Payment(static::generateCardSource(), $currency);
        $payment->approved = true;
        $payment->risk['flagged'] = false;
        $payment->_links['redirect']['href'] = HttpHandlers::generateURL();
        $payment->{'3ds'} = Payments::generateTheeDS();
        return $payment;
    }

    public static function generateVoidModel($id = '')
    {
        return new Voids($id);
    }

    public static function generateRefundModel($id = '')
    {
        return new Refund($id);
    }

    public static function generateCaptureModel($id = '')
    {
        return new Capture($id);
    }

    public static function generateCardSource()
    {
        $source = new CardSource('4242424242424242', 01, 2025);
        $source->cvv = 100;
        $source->name = 'Joe Smith';
        $source->id = Sources::generateSourceID();

        return $source;
    }

    public static function generateTheeDS()
    {
        return new ThreeDs(true);
    }

    public static function generateActionsModel()
    {
        return new Action(static::generateID());
    }
}
