<?php

namespace Checkout\Instruments;

class CreateInstrumentRequest
{

    public string $type;

    public string $token;

    public InstrumentAccountHolder $account_holder;

    public InstrumentCustomerRequest $customer;

}
