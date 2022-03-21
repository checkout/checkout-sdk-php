<?php

namespace Checkout\Instruments;

class UpdateInstrumentRequest
{
    public $expiry_month;

    public $expiry_year;

    public $name;

    // InstrumentAccountHolder
    public $account_holder;

    // UpdateInstrumentCustomerRequest
    public $customer;
}
