<?php

namespace Checkout\Payments\Sessions;

use Checkout\Common\Address;
use Checkout\Common\Phone;

class Billing
{
    /**
     * The billing address information.
     * @var Address
     */
    public $address;

    /**
     * The billing phone number.
     * @var Phone
     */
    public $phone;
}
