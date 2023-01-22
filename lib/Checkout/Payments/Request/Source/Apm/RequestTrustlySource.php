<?php

namespace Checkout\Payments\Request\Source\Apm;

use Checkout\Common\Address;
use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Request\Source\AbstractRequestSource;

class RequestTrustlySource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$trustly);
    }

    /**
     * @var Address
     */
    public $billing_address;
}
