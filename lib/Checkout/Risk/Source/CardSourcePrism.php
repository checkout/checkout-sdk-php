<?php

namespace Checkout\Risk\Source;

use Checkout\Common\Address;
use Checkout\Common\PaymentSourceType;
use Checkout\Common\Phone;

class CardSourcePrism extends RiskPaymentRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$card);
    }

    /**
     * @var string
     */
    public $number;

    /**
     * @var int
     */
    public $expiry_month;

    /**
     * @var int
     */
    public $expiry_year;

    /**
     * @var string
     */
    public $name;

    /**
     * @var Address
     */
    public $billing_address;

    /**
     * @var Phone
     */
    public $phone;
}
