<?php

namespace Checkout\Payments\Four\Request\Source\Apm;

use Checkout\Common\Address;
use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Four\Request\Source\AbstractRequestSource;

class RequestTamaraSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$tamara);
    }

    /**
     * @var Address
     */
    public $billing_address;
}
