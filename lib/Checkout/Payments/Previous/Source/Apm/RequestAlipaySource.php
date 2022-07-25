<?php

namespace Checkout\Payments\Previous\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Previous\Source\AbstractRequestSource;

class RequestAlipaySource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$alipay);
    }
}
