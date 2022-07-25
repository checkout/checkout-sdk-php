<?php

namespace Checkout\Payments\Request\Source;

use Checkout\Common\Address;
use Checkout\Common\PaymentSourceType;
use Checkout\Common\Phone;

class RequestNetworkTokenSource extends AbstractRequestSource
{

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$network_token);
    }

    /**
     * @var string
     */
    public $token;

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
    public $token_type;

    /**
     * @var string
     */
    public $cryptogram;

    /**
     * @var string
     */
    public $eci;

    /**
     * @var bool
     */
    public $stored;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $cvv;

    /**
     * @var Address
     */
    public $billing_address;

    /**
     * @var Phone
     */
    public $phone;
}
