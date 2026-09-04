<?php

namespace Checkout\Instruments\Update;

/**
 * The account holder details of an ACH instrument being updated.
 *
 * The specification marks all four properties as required, but the descriptions qualify that: the
 * names apply to an individual account holder and the company name to a corporate one. That is a
 * conditional requirement the specification cannot express, so it is not enforced here.
 *
 * This deliberately does not use Checkout\Common\AccountHolder, which is a superset carrying a
 * phone number, identification, a date of birth and a tax ID that the ACH instrument schema does not
 * declare. It also has no billing address: the ACH account holder schema does not declare one.
 */
class UpdateAchAccountHolder
{
    /**
     * First name. Required for individual account holder type.
     * [Required]
     * @var string
     */
    public $first_name;

    /**
     * Last name. Required for individual account holder type.
     * [Required]
     * @var string
     */
    public $last_name;

    /**
     * Company name. Required for corporate account holder type.
     * [Required]
     * @var string
     */
    public $company_name;

    /**
     * Account holder type.
     * [Required]
     * @var string values of Checkout\Instruments\InstrumentAccountHolderType
     */
    public $type;
}
