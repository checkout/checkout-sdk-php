<?php

namespace Checkout\Instruments;

class UpdateInstrumentRequest
{
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
    public $name;

    /**
     * @var InstrumentAccountHolder
     */
    public $account_holder;

    /**
     * @var UpdateInstrumentCustomerRequest
     */
    public $customer;
}
