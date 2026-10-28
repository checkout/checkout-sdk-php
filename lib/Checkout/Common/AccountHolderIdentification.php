<?php

namespace Checkout\Common;

/**
 * The account holder's identification.
 * Shared by payment sources, payment and payout senders, card payout and unreferenced refund
 * account holders, ACH account holders and Accounts account holders. Required flags and length
 * constraints differ per position, as noted on each property.
 */
class AccountHolderIdentification
{
    /**
     * The type of identification for the account holder.
     * [Required] for card payout and unreferenced refund account holders, [Optional] elsewhere
     * @var string value of AccountHolderIdentificationType
     */
    public $type;

    /**
     * The account holder's identification number.
     * [Required] for card payout and unreferenced refund account holders, [Optional] elsewhere
     * max 25 characters for card payout and unreferenced refund account holders
     * @var string
     */
    public $number;

    /**
     * If applicable, the country that issued the account holder's identification, as a two-letter ISO country code.
     * Providing issuing_country increases the likelihood of a successful identity verification.
     * [Optional]
     * min 2 characters and max 2 characters for card payout and unreferenced refund account holders
     * @var string value of Country
     */
    public $issuing_country;

    /**
     * If applicable, the expiration date of the account holder's identification, in the format YYYY-MM-DD.
     * Providing date_of_expiry increases the likelihood of a successful identity verification.
     * Card payout and unreferenced refund account holders only.
     * [Optional]
     * min 10 characters and max 10 characters
     * @var string|null
     */
    public $date_of_expiry;
}
