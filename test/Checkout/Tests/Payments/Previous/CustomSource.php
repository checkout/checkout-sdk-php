<?php

namespace Checkout\Tests\Payments\Previous;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Previous\Source\AbstractRequestSource;

class CustomSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$alipay);
    }

    public $amount;

    public $currency;
}
