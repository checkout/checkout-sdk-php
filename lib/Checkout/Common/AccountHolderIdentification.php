<?php

namespace Checkout\Common;

class AccountHolderIdentification
{
    /**
     * The type of identification for the account holder.
     * [Optional]
     * @var string value of AccountHolderIdentificationType
     */
    public $type;

    /**
     * The account holder's identification number.
     * [Optional]
     * max 25 characters
     * @var string
     */
    public $number;

    /**
     * If applicable, the country that issued the account holder's identification, as a two-letter ISO country code.
     * [Optional]
     * min 2 characters
     * max 2 characters
     * @var string value of Country
     */
    public $issuing_country;

    /**
     * If applicable, the expiration date of the account holder's identification, in the format YYYY-MM-DD.
     * Providing date_of_expiry increases the likelihood of a successful identity verification.
     * [Optional]
     * Format: yyyy-MM-dd
     * min 10 characters
     * max 10 characters
     * @var string|null
     */
    public $date_of_expiry;
}
