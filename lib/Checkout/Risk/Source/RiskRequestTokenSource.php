<?php

namespace Checkout\Risk\Source;

use Checkout\Common\Address;
use Checkout\Common\PaymentSourceType;
use Checkout\Common\Phone;

class RiskRequestTokenSource extends RiskPaymentRequestSource
{

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$token);
    }

    /**
     * @var string
     */
    public $token;

    /**
     * @var Address
     */
    public $billing_address;

    /**
     * @var Phone
     */
    public $phone;
}
