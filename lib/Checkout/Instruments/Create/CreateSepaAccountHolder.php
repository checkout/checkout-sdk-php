<?php

namespace Checkout\Instruments\Create;

/**
 * The account holder details of a SEPA instrument being stored.
 *
 * This deliberately does not use Checkout\Common\AccountHolder, which is a superset carrying
 * fields the SEPA instrument schema does not declare.
 */
class CreateSepaAccountHolder
{
    /**
     * The first name of the account holder.
     * [Required]
     * @var string
     */
    public $first_name;

    /**
     * The last name of the account holder.
     * [Required]
     * @var string
     */
    public $last_name;

    /**
     * The legal name of a registered company that holds the account.
     * [Optional]
     * max 50 characters
     * @var string
     */
    public $company_name;

    /**
     * The billing address of the account holder.
     * [Required]
     * @var CreateSepaBillingAddress
     */
    public $billing_address;

    /**
     * The type of account holder.
     * [Optional]
     * @var string values of Checkout\Instruments\InstrumentAccountHolderType
     */
    public $type;
}
