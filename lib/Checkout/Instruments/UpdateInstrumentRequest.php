<?php

namespace Checkout\Instruments;

class UpdateInstrumentRequest
{
    public int $expiry_month;

    public int $expiry_year;

    public string $name;

    public InstrumentAccountHolder $account_holder;

    public UpdateInstrumentCustomerRequest $customer;
}
