<?php

namespace Checkout\Payments\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Source\AbstractRequestSource;

class RequestAlipaySource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$alipay);
    }
}
