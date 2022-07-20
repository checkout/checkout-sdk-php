<?php

namespace Checkout\Payments\Four\Request\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Four\Request\Source\AbstractRequestSource;

class RequestAlipayPlusGCashSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$gcash);
    }
}
