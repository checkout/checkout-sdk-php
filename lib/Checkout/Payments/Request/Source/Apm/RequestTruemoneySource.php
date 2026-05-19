<?php

namespace Checkout\Payments\Request\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Request\Source\AbstractRequestSource;

class RequestTruemoneySource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$truemoney);
    }
}
