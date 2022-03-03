<?php

namespace Checkout\Payments\Source;

use Checkout\Common\PaymentSourceType;

class RequestIdSource extends AbstractRequestSource
{

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$id);
    }

    public string $id;

    public string $cvv;

}
