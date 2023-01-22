<?php

namespace Checkout\Payments\Request\Source\Apm;

use Checkout\Common\Address;
use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Request\Source\AbstractRequestSource;

class RequestCvConnectSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$cvconnect);
    }

    /**
     * @var Address
     */
    public $billing_address;
}
