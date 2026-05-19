<?php

namespace Checkout\Payments\Request\Source\Apm;

use Checkout\Common\Address;
use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Request\Source\AbstractRequestSource;

class RequestSequraSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$sequra);
    }

    /**
     * @var Address
     */
    public $billing_address;
}
