<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Bacs;

class BacsAccountHolder
{
    /**
     * The type of account holder.
     * [Optional]
     * Enum: value of BacsAccountHolderType
     * @var string
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
     * The email address of the account holder.
     * [Optional]
     * @var string
     */
    public $email;
}
