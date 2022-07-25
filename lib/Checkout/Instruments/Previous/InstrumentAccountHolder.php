<?php

namespace Checkout\Instruments\Previous;

use Checkout\Common\Address;
use Checkout\Common\Phone;

class InstrumentAccountHolder
{
    /**
     * @var Address
     */
    public $billing_address;

    /**
     * @var Phone
     */
    public $phone;
}
