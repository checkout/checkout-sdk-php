<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Sepa;

/**
 * The SEPA account holder details.
 */
class SepaAccountHolder
{
    /**
     * The type of account holder.
     * [Optional]
     * @var string value of SepaAccountHolderType
     */
    public $type;

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
     * @var string
     */
    public $company_name;
}
