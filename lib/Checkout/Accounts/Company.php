<?php

namespace Checkout\Accounts;

use Checkout\Common\Address;

class Company
{
    /**
     * @var string
     */
    public $business_registration_number;

    /**
     * @var string value of BusinessType
     */
    public $business_type;

    /**
     * @var string
     */
    public $legal_name;

    /**
     * @var string
     */
    public $trading_name;

    /**
     * @var array of string additional names the company trades under (v3.0)
     */
    public $additional_trading_names;

    /**
     * @var bool whether the entity is a registered company (v3.0)
     */
    public $is_registered_company;

    /**
     * @var DateOfIncorporation the date the company was incorporated (v3.0)
     */
    public $date_of_incorporation;

    /**
     * @var Address
     */
    public $principal_address;

    /**
     * @var Address
     */
    public $registered_address;

    /**
     * @var EntityDocument
     */
    public $document;

    /**
     * @var array of Representative
     */
    public $representatives;

    /**
     * @var EntityFinancialDetails
     */
    public $financial_details;
}
