<?php

namespace Checkout\Common;

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
     * @var string value of AccountHolderType
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
     * @var string values of Country
     */
    public $country_of_birth;

    /**
     * @var string value of ResidentialStatusType
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

    /**
     * @var string
     */
    public $gender;

    /**
     * @var string
     */
    public $middle_name;
}
