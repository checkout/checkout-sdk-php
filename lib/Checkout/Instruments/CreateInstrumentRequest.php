<?php

namespace Checkout\Instruments;

class CreateInstrumentRequest
{

    public $type;

    public $token;

    // InstrumentAccountHolder
    public $account_holder;

    // InstrumentCustomerRequest
    public $customer;

}
