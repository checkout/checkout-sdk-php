<?php

namespace Checkout\Instruments\Update;

/**
 * The account holder details of a Bacs Direct Debit instrument being updated.
 *
 * The update schema adds company_name and type on top of the store shape. It deliberately does not
 * use Checkout\Common\AccountHolder, which is a superset carrying fields this schema does not
 * declare.
 */
class UpdateBacsAccountHolder
{
    /**
     * The first name of the account holder.
     * [Optional]
     * @var string
     */
    public $first_name;

    /**
     * The last name of the account holder.
     * [Optional]
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
     * [Optional]
     * @var UpdateBacsBillingAddress
     */
    public $billing_address;

    /**
     * The type of account holder.
     * [Optional]
     * @var string values of Checkout\Instruments\InstrumentAccountHolderType
     */
    public $type;
}
