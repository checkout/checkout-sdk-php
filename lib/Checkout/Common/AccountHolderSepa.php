<?php

namespace Checkout\Common;

/**
 * Account holder details for SEPA payment sources.
 *
 * Mirrors the `account_holder` object of the `PaymentRequestSEPAV4Source` schema - a
 * narrower shape than {@see AccountHolder}, with only the five fields the API accepts
 * at this position.
 */
class AccountHolderSepa
{
    /**
     * The account holder's billing address.
     * [Required] All five of its properties are required.
     *
     * @var SepaSourceBillingAddress
     */
    public $billing_address;

    /**
     * The account holder's first name.
     * [Optional] max 50 characters
     *
     * @var string
     */
    public $first_name;

    /**
     * The account holder's last name.
     * [Optional] max 50 characters
     *
     * @var string
     */
    public $last_name;

    /**
     * The account holder's company name.
     * [Optional] max 50 characters
     *
     * @var string
     */
    public $company_name;

    /**
     * The type of account holder.
     * [Optional]
     *
     * Send this lowercase (individual, corporate). The specification declares it
     * capitalized at this one position, but every other account-holder-type position
     * declares it lowercase and every other Checkout.com SDK sends lowercase. Pending
     * confirmation from the API owners.
     *
     * @var string value of InstrumentAccountHolderType
     */
    public $type;
}
