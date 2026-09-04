<?php

namespace Checkout\Instruments\Create;

/**
 * The account holder details of a Bacs Direct Debit instrument being stored.
 *
 * The store schema declares first_name, last_name and billing_address only. It deliberately does
 * not use Checkout\Common\AccountHolder, which is a superset carrying fields this schema does not
 * declare.
 */
class CreateBacsAccountHolder
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
     * The billing address of the account holder.
     * [Required]
     * @var CreateBacsBillingAddress
     */
    public $billing_address;
}
