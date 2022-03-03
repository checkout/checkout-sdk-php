<?php

namespace Checkout\Instruments\Four\Update;

use Checkout\Common\Four\AccountHolder;
use Checkout\Common\InstrumentType;

class UpdateCardInstrumentRequest extends UpdateInstrumentRequest
{
    public function __construct()
    {
        parent::__construct(InstrumentType::$card);
    }

    public int $expiry_month;

    public int $expiry_year;

    public string $name;

    public UpdateCustomerRequest $customer;

    public AccountHolder $account_holder;
}
