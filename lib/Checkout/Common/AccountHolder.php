<?php

namespace Checkout\Common;

/**
 * The account holder of a payout or unreferenced refund destination, a payment source, a payment
 * session payment method or an instrument.
 * Shared by many positions, so required flags and length constraints differ per position, as noted on
 * each property. Per-position notes cover card payout destinations (card, network_token, id, token),
 * unreferenced refund destinations and bank payout bank_account destinations; for other positions, see
 * the enclosing request.
 * Card payout destinations: card and network_token accept type, first_name, middle_name, last_name,
 * billing_address, phone, identification, email, date_of_birth and country_of_birth for individuals, and
 * type, company_name, billing_address, phone, identification and email for corporate and government; id
 * and token accept type, first_name, middle_name and last_name, or type and company_name.
 * Unreferenced refund destinations accept the card and network_token keys above on every destination.
 * Bank payout destinations accept type, first_name, last_name, tax_id, date_of_birth, country_of_birth,
 * residential_status, billing_address, phone and identification for individuals, and type,
 * company_name, tax_id and billing_address for corporate and government.
 */
class AccountHolder
{
    /**
     * The account holder's first name.
     * For card payouts and unreferenced refunds, the combination of first_name, middle_name, and
     * last_name should exceed 3 letters in length, and it must be a valid legal name (a single
     * character or all non-alphabetic characters return a field validation error). For card payout id
     * and token destinations, not required if the first name is already stored with the token or
     * payment instrument. For bank payouts, the legal first name of the account holder.
     * [Required] for individual card payout and bank payout account holders and for individual card and
     * network_token unreferenced refund account holders, [Optional] for individual id and token
     * unreferenced refund account holders
     * max 50 characters for card payouts and unreferenced refunds
     * @var string
     */
    public $first_name;

    /**
     * The account holder's last name.
     * For card payouts and unreferenced refunds, the combination of first_name, middle_name, and
     * last_name should exceed 3 letters in length, and it must be a valid legal name (a single
     * character or all non-alphabetic characters return a field validation error). For card payout id
     * and token destinations, not required if the last name is already stored with the token or
     * payment instrument. For bank payouts, the legal last name of the account holder.
     * [Required] for individual card payout and bank payout account holders and for individual card and
     * network_token unreferenced refund account holders, [Optional] for individual id and token
     * unreferenced refund account holders
     * max 50 characters for card payouts and unreferenced refunds
     * @var string
     */
    public $last_name;

    /**
     * The account holder's billing address.
     * For card payouts and unreferenced refunds, if your company is incorporated in the United States,
     * this field is required for all card payouts and unreferenced refunds you perform. For bank
     * payouts, the current billing address associated with the destination account; strongly
     * recommended when available.
     * [Required] for individual bank payout account holders, [Optional] for every other payout and
     * unreferenced refund account holder
     * Not part of the card payout id and token destination account holders.
     * @var Address
     */
    public $billing_address;

    /**
     * The account holder's phone number.
     * For card payouts and unreferenced refunds, country_code (min 1, max 7 characters) and number
     * (min 6, max 25 characters) are required inside the object.
     * [Optional]
     * Not part of the card payout id and token destination account holders, nor of corporate and
     * government bank payout account holders.
     * @var Phone
     */
    public $phone;

    /**
     * The type of the account holder, that is, the type of account receiving the payout or
     * unreferenced refund.
     * [Required] for card payout and bank payout account holders and for corporate and government
     * unreferenced refund account holders, [Optional] for individual unreferenced refund account
     * holders of id and token destinations
     * Enum: "individual" "corporate" "government"
     * @var string value of AccountHolderType
     */
    public $type;

    /**
     * The account holder's company name. For card payouts and unreferenced refunds, it must be a valid
     * legal name (a single character or all non-alphabetic characters return a field validation
     * error). For bank payouts, the full legal name of a registered company that holds the account.
     * [Required] for corporate and government payout and unreferenced refund account holders
     * max 50 characters for card payouts and unreferenced refunds
     * @var string
     */
    public $company_name;

    /**
     * The legal account holder's tax number or reference.
     * Bank payouts only.
     * [Optional]
     * @var string
     */
    public $tax_id;

    /**
     * The account holder's date of birth, in the format YYYY-MM-DD.
     * For bank payouts, strongly recommended where available.
     * Individual account holders only.
     * [Optional]
     * min 10 characters and max 10 characters for card payouts and unreferenced refunds
     * Format: date for bank payouts
     * @var string
     */
    public $date_of_birth;

    /**
     * The account holder's country of birth, as a two-letter ISO country code.
     * Individual account holders only.
     * [Optional]
     * min 2 characters and max 2 characters for card payouts and unreferenced refunds
     * @var string values of Country
     */
    public $country_of_birth;

    /**
     * The account holder's residential status.
     * Individual bank payout account holders only.
     * [Optional]
     * Enum: "resident" "non_resident"
     * @var string value of ResidentialStatusType
     */
    public $residential_status;

    /**
     * The account holder's identification.
     * For card payouts and unreferenced refunds, providing identification details for the recipient
     * increases the likelihood of a successful payout. For individual bank payout account holders, the
     * account holder's government document identification, for example, the social security number.
     * [Optional]
     * Not part of the card payout id and token destination account holders, nor of corporate and
     * government bank payout account holders.
     * @var AccountHolderIdentification
     */
    public $identification;

    /**
     * The account holder's email address.
     * Card payouts and unreferenced refunds only.
     * [Optional]
     * max 255 characters
     * @var string
     */
    public $email;

    /**
     * The account holder's gender.
     * [Optional]
     *
     * @deprecated Not in the request schema of any account holder. Still returned in the Klarna payment
     * details source account_holder.
     * Retained for backwards compatibility and will be removed in the next major version. There is no
     * replacement.
     * @var string
     */
    public $gender;

    /**
     * The account holder's middle name. For card payouts and unreferenced refunds, the combination of
     * first_name, middle_name, and last_name should exceed 3 letters in length.
     * Individual card payout and unreferenced refund account holders only.
     * [Optional]
     * max 50 characters
     * @var string
     */
    public $middle_name;

    /**
     * Whether the account name inquiry (ANI) check should be performed with the scheme (Visa or
     * Mastercard).
     * Payment source, payment setup and payment session account holders only; not part of any payout or unreferenced
     * refund account holder.
     * [Optional]
     * @var bool
     */
    public $account_name_inquiry;
}
