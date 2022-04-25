<?php

namespace Checkout\Risk\Source;

use Checkout\Common\PaymentSourceType;

class IdSourcePrism extends RiskPaymentRequestSource
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $cvv;

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$id);
    }
}
