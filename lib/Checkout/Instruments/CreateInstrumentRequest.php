<?php

namespace Checkout\Instruments;

use Checkout\Common\InstrumentType;

class CreateInstrumentRequest
{

    /**
     * @var InstrumentType
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
