<?php

namespace Checkout\Instruments\Four\Create;

use Checkout\Common\InstrumentType;

class CreateTokenInstrumentRequest extends CreateInstrumentRequest
{

    public function __construct()
    {
        parent::__construct(InstrumentType::$token);
    }

    public $token;

    // AccountHolder
    public $account_holder;

    // CreateCustomerInstrumentRequest
    public $customer;

}
