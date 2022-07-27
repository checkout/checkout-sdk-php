<?php

namespace Checkout\Instruments\Previous;

class CreateInstrumentRequest
{

    /**
     * @var string value of InstrumentType
     */
    public $type;

    /**
     * @var string
     */
    public $token;

    /**
     * @var InstrumentAccountHolder
     */
    public $account_holder;

    /**
     * @var InstrumentCustomerRequest
     */
    public $customer;
}
