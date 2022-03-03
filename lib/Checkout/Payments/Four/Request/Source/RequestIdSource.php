<?php

namespace Checkout\Payments\Four\Request\Source;

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
