<?php

namespace Checkout\Risk\source;

use Checkout\Common\PaymentSourceType;

class CustomerSourcePrism extends RiskPaymentRequestSource
{
    public string $id;

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$customer);
    }


}
