<?php

namespace Checkout\Payments;

class AccountInfo
{
    /**
     * The number of purchases with this cardholder's account during the last six months.
     * [Optional]
     * min 0
     * max 9999
     * @var int|null $purchase_count
     */
    public $purchase_count;

    /**
     * The length of time that the payment account was enrolled in the cardholder's account.
     * [Optional]
     * Enum: "no_account" "created_during_transaction" "less_than_thirty_days"
     *       "thirty_to_sixty_days" "more_than_sixty_days"
     * @var string|null $account_age
     */
    public $account_age;

    /**
     * The number of Add Card attempts in the last 24 hours.
     * [Optional]
     * min 0
     * max 999
     * @var int|null $add_card_attempts
     */
    public $add_card_attempts;

    /**
     * Indicates when the shipping address for this transaction was first used.
     * [Optional]
     * Enum: "this_transaction" "less_than_thirty_days" "thirty_to_sixty_days" "more_than_sixty_days"
     * @var string|null $shipping_address_age
     */
    public $shipping_address_age;

    /**
     * Indicates if the cardholder name on the account is identical to the shipping name used for this transaction.
     * [Optional]
     * @var bool|null $account_name_matches_shipping_name
     */
    public $account_name_matches_shipping_name;

    /**
     * Indicates whether suspicious activity on the cardholder account has been observed.
     * [Optional]
     * @var bool|null $suspicious_account_activity
     */
    public $suspicious_account_activity;

    /**
     * The number of transactions (successful and abandoned) for the cardholder account across
     * all payment accounts in the previous 24 hours.
     * [Optional]
     * min 0
     * max 999
     * @var int|null $transactions_today
     */
    public $transactions_today;

    /**
     * Information about how the cardholder was authenticated before or during the transaction.
     * [Optional]
     * Enum: "no_authentication" "own_credentials" "federated_id" "issuer_credentials"
     *       "third_party_authentication" "fido"
     * @var string|null $authentication_method
     */
    public $authentication_method;

    /**
     * The length of time the cardholder has held the account with the 3DS Requestor.
     * [Optional]
     * Enum: "no_account" "this_transaction" "less_than_thirty_days"
     *       "thirty_to_sixty_days" "more_than_sixty_days"
     * @var string|null $cardholder_account_age_indicator
     */
    public $cardholder_account_age_indicator;

    /**
     * The UTC date and time the cardholder's account with the 3DS Requestor was last changed, in ISO 8601 format.
     * Changes include: updating billing or shipping address, adding a new payment account or user.
     * [Optional]
     * Format: date-time
     * @var string|null $account_change
     */
    public $account_change;

    /**
     * The amount of time since the cardholder's account information with the 3DS Requestor was last changed.
     * [Optional]
     * Enum: "this_transaction" "less_than_thirty_days" "thirty_to_sixty_days" "more_than_sixty_days"
     * @var string|null $account_change_indicator
     */
    public $account_change_indicator;

    /**
     * The UTC date and time the cardholder opened the account with the 3DS Requestor, in ISO 8601 format.
     * [Optional]
     * Format: date-time
     * @var string|null $account_date
     */
    public $account_date;

    /**
     * The UTC date and time the cardholder's account with the 3DS Requestor was last reset or
     * had a password change, in ISO 8601 format.
     * [Optional]
     * Format: date-time
     * @var string|null $account_password_change
     */
    public $account_password_change;

    /**
     * The amount of time since the cardholder's account with the 3DS Requestor was last reset
     * or had a password change.
     * [Optional]
     * Enum: "no_change" "this_transaction" "less_than_thirty_days"
     *       "thirty_to_sixty_days" "more_than_sixty_days"
     * @var string|null $account_password_change_indicator
     */
    public $account_password_change_indicator;

    /**
     * The number of transactions associated with the cardholder's account with the 3DS Requestor
     * in the previous year. Set to 999 if the number of transactions is 1000 or greater.
     * [Optional]
     * max 999
     * @var int|null $transactions_per_year
     */
    public $transactions_per_year;

    /**
     * The UTC date and time the payment account was enrolled in the cardholder's account with
     * the 3DS Requestor, in ISO 8601 format.
     * [Optional]
     * Format: date-time
     * @var string|null $payment_account_age
     */
    public $payment_account_age;

    /**
     * The UTC date and time the shipping address used for the transaction was first used with
     * the 3DS Requestor, in ISO 8601 format.
     * [Optional]
     * Format: date-time
     * @var string|null $shipping_address_usage
     */
    public $shipping_address_usage;

    /**
     * The type of account, in the case of a card product with multiple accounts.
     * [Optional]
     * Enum: "not_applicable" "credit" "debit"
     * @var string|null $account_type
     */
    public $account_type;

    /**
     * Additional information about the account optionally provided by the 3DS Requestor.
     * [Optional]
     * max 64 characters
     * @var string|null $account_id
     */
    public $account_id;
}
