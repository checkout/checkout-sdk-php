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
