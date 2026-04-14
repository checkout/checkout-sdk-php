<?php

namespace Checkout\AgenticCommerce\Entities;

/**
 * The card payment method details for a delegated payment (OpenAPI DelegatedPaymentMethodCard).
 */
class DelegatedPaymentMethodCard
{
    /**
     * The payment method type.
     * [Required]
     * Enum: "card"
     *
     * @var string value of DelegatedPaymentCardType
     */
    public $type;

    /**
     * The type of card number provided: fpan (Funding Primary Account Number on the card) or network_token (provisioned network token).
     * [Required]
     * Enum: "fpan" "network_token"
     *
     * @var string value of DelegatedPaymentCardNumberType
     */
    public $card_number_type;

    /**
     * The full card number.
     * [Required]
     *
     * @var string
     */
    public $number;

    /**
     * The card expiry month, in two-digit format (MM).
     * [Optional]
     * Length: 2 characters
     *
     * @var string|null
     */
    public $exp_month;

    /**
     * The card expiry year, in four-digit format (YYYY).
     * [Optional]
     * Length: 4 characters
     *
     * @var string|null
     */
    public $exp_year;

    /**
     * The name of the cardholder as it appears on the card.
     * [Optional]
     *
     * @var string|null
     */
    public $name;

    /**
     * The card verification code (CVC/CVV).
     * [Optional]
     * Length: 3–4 characters
     *
     * @var string|null
     */
    public $cvc;

    /**
     * The cryptogram associated with a network token transaction. Required when card_number_type is network_token.
     * [Optional] (conditionally required when card_number_type is network_token)
     *
     * @var string|null
     */
    public $cryptogram;

    /**
     * The Electronic Commerce Indicator (ECI) or Security Level Indicator (SLI) value for network token transactions.
     * [Optional]
     *
     * @var string|null
     */
    public $eci_value;

    /**
     * A list of verification checks that have been performed on the card (for example avs, cvv, ani, auth0).
     * [Optional]
     *
     * @var string[]|null
     */
    public $checks_performed;

    /**
     * The Issuer Identification Number (IIN), also known as the Bank Identification Number; typically the first 6 digits of the card number.
     * [Optional]
     * Length: 6 characters
     *
     * @var string|null
     */
    public $iin;

    /**
     * The funding type of the card, used for display purposes.
     * [Optional]
     * Enum: "credit" "debit" "prepaid"
     *
     * @var string|null value of DelegatedPaymentDisplayCardFundingType
     */
    public $display_card_funding_type;

    /**
     * The wallet type associated with the card, used for display purposes (for example Apple Pay or Google Pay).
     * [Optional]
     *
     * @var string|null
     */
    public $display_wallet_type;

    /**
     * The card brand, used for display purposes (for example Visa, Mastercard).
     * [Optional]
     *
     * @var string|null
     */
    public $display_brand;

    /**
     * The last four digits of the card number, used for display purposes.
     * [Optional]
     * Length: 4 characters
     *
     * @var string|null
     */
    public $display_last4;

    /**
     * A set of key-value pairs containing additional payment method metadata (string values only).
     * [Required]
     *
     * @var array<string, string>|null
     */
    public $metadata;
}
