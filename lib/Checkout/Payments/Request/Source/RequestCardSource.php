<?php

namespace Checkout\Payments\Request\Source;

use Checkout\Common\Address;
use Checkout\Common\PaymentSourceType;
use Checkout\Common\Phone;

class RequestCardSource extends AbstractRequestSource
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
     * @var string
     */
    public $cvv;

    /**
     * @var bool
     */
    public $stored;

    /**
     * @var bool
     */
    public $store_for_future_use;

    /**
     * @var Address
     */
    public $billing_address;

    /**
     * @var Phone
     */
    public $phone;
}
