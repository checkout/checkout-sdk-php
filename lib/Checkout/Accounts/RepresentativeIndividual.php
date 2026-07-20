<?php

namespace Checkout\Accounts;

use Checkout\Common\Address;
use Checkout\Common\Phone;

class RepresentativeIndividual
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
     * @var DateOfBirth
     */
    public $date_of_birth;

    /**
     * @var PlaceOfBirth
     */
    public $place_of_birth;

    /**
     * @var string
     */
    public $national_id_number;

    /**
     * @var string
     */
    public $email_address;

    /**
     * @var Phone
     */
    public $phone;

    /**
     * @var Address
     */
    public $address;
}
