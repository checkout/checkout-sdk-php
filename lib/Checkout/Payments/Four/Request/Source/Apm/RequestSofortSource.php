<?php

namespace Checkout\Payments\Four\Request\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Four\Request\Source\AbstractRequestSource;

class RequestSofortSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$sofort);
    }
}
