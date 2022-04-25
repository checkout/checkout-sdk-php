<?php

namespace Checkout\Instruments;

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
