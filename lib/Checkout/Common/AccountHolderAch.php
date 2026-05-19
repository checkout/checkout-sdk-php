<?php

namespace Checkout\Common;

/**
 * Account holder details for ACH payment sources.
 *
 * Mirrors the `AccountHolderAch` schema in the swagger spec — a narrower shape
 * than {@see AccountHolder}, with only the fields the API accepts for ACH.
 */
class AccountHolderAch
{
    /**
     * @var string value of AccountHolderType (individual, corporate, government)
     */
    public $type;

    /**
     * @var string
     */
    public $first_name;

    /**
     * @var string
     */
    public $last_name;

    /**
     * @var string
     */
    public $company_name;

    /**
     * @var Address
     */
    public $billing_address;

    /**
     * @var string
     */
    public $date_of_birth;

    /**
     * @var AccountHolderIdentification
     */
    public $identification;
}
