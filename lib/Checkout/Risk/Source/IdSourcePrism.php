<?php

namespace Checkout\Risk\Source;

use Checkout\Common\PaymentSourceType;

class IdSourcePrism extends RiskPaymentRequestSource
{

    public $id;

    public $cvv;

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$id);
    }


}
