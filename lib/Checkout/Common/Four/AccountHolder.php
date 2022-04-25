<?php

namespace Checkout\Common\Four;

use Checkout\Common\Address;
use Checkout\Common\Phone;

class AccountHolder
{
    /**
     * @var string
     */
    public $first_name;

    /**
     * @var string
     */
    public $last_name;

    /**
     * @var Address
     */
    public $billing_address;

    /**
     * @var Phone
     */
    public $phone;
}
