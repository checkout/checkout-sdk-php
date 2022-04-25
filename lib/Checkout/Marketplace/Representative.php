<?php

namespace Checkout\Marketplace;

use Checkout\Common\Address;
use Checkout\Common\Phone;

class Representative
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
    public $address;

    /**
     * @var Identification
     */
    public $identification;

    /**
     * @var Phone
     */
    public $phone;

    /**
     * @var DateOfBirth
     */
    public $date_of_birth;
}
