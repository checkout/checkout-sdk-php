<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Ach;

/**
 * The ACH account holder details.
 */
class AchAccountHolder
{
    /**
     * The type of account holder.
     * [Optional]
     * @var string value of AchAccountHolderType
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

    /**
     * The account holder's date of birth.
     * [Optional]
     * @var string
     */
    public $date_of_birth;

    /**
     * The account holder's government document identification, for example a Social Security Number
     * (SSN).
     * [Optional]
     * writeOnly
     * @var AchAccountHolderIdentification
     */
    public $identification;
}
