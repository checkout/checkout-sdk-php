<?php

namespace Checkout\Risk\Source;

use Checkout\Common\PaymentSourceType;

class CustomerSourcePrism extends RiskPaymentRequestSource
{
    public $id;

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$customer);
    }


}
