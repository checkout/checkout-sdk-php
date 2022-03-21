<?php

namespace Checkout\Payments\Source;

use Checkout\Common\PaymentSourceType;

class RequestCardSource extends AbstractRequestSource
{

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$card);
    }

    public $number;

    public $expiry_month;

    public $expiry_year;

    public $name;

    public $cvv;

    public $stored;

    // Address
    public $billing_address;

    // Phone
    public $phone;

}
