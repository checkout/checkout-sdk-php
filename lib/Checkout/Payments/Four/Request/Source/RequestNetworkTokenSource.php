<?php

namespace Checkout\Payments\Four\Request\Source;

use Checkout\Common\PaymentSourceType;

class RequestNetworkTokenSource extends AbstractRequestSource
{

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$network_token);
    }

    public $token;

    public $expiry_month;

    public $expiry_year;

    public $token_type;

    public $cryptogram;

    public $eci;

    public $stored;

    public $name;

    public $cvv;

    // Address
    public $billing_address;

    // Phone
    public $phone;

}
