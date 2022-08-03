<?php

namespace Checkout\Accounts;

use Checkout\Common\Address;

class Individual
{
    /**
     * @var string
     */
    public $first_name;

    /**
     * @var string
     */
    public $middle_name;

    /**
     * @var string
     */
    public $last_name;

    /**
     * @var string
     */
    public $trading_name;

    /**
     * @var string
     */
    public $national_tax_id;

    /**
     * @var Address
     */
    public $registered_address;

    /**
     * @var DateOfBirth
     */
    public $date_of_birth;

    /**
     * @var PlaceOfBirth
     */
    public $place_of_birth;

    /**
     * @var Identification
     */
    public $identification;
}
