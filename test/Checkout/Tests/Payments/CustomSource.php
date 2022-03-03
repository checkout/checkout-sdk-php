<?php

namespace Checkout\Tests\Payments;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Source\AbstractRequestSource;

class CustomSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$alipay);
    }

    public int $amount;

    public string $currency;
}
