<?php

namespace Checkout\Payments;

use Checkout\Common\Address;
use Checkout\Common\Phone;

class BillingInformation
{
    /**
     * @var Address
     */
    public $address;

    /**
     * @var Phone
     */
    public $phone;
}
