<?php

namespace Checkout\Risk\source;

use Checkout\Common\PaymentSourceType;

class IdSourcePrism extends RiskPaymentRequestSource
{

    public string $id;

    public string $cvv;

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$id);
    }


}
