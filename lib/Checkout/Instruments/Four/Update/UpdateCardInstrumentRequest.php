<?php

namespace Checkout\Instruments\Four\Update;

use Checkout\Common\InstrumentType;

class UpdateCardInstrumentRequest extends UpdateInstrumentRequest
{
    public function __construct()
    {
        parent::__construct(InstrumentType::$card);
    }

    public $expiry_month;

    public $expiry_year;

    public $name;

    // UpdateCustomerRequest
    public $customer;

    // AccountHolder
    public $account_holder;
}
