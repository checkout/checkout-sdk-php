<?php

namespace Checkout\Instruments\Four\Create;

use Checkout\Common\Four\AccountHolder;
use Checkout\Common\InstrumentType;

class CreateTokenInstrumentRequest extends CreateInstrumentRequest
{

    public function __construct()
    {
        parent::__construct(InstrumentType::$token);
    }

    public string $token;

    public AccountHolder $account_holder;

    public CreateCustomerInstrumentRequest $customer;

}
