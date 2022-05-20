<?php

namespace Checkout\Common\Four;

use Checkout\Common\Address;
use Checkout\Common\Country;
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

    /**
     * @var AccountHolderType
     */
    public $type;

    /**
     * @var string
     */
    public $company_name;

    /**
     * @var string
     */
    public $tax_id;

    /**
     * @var string
     */
    public $date_of_birth;

    /**
     * @var Country
     */
    public $country_of_birth;

    /**
     * @var ResidentialStatusType
     */
    public $residential_status;

    /**
     * @var AccountHolderIdentification
     */
    public $identification;

    /**
     * @var string
     */
    public $email;
}
