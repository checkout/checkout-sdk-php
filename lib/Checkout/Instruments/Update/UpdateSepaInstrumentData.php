<?php

namespace Checkout\Instruments\Update;

/**
 * The details of the SEPA account being updated.
 */
class UpdateSepaInstrumentData
{
    /**
     * The type of mandate.
     * [Optional]
     * @var string values of Checkout\Instruments\SepaMandateType
     */
    public $type;

    /**
     * The International Bank Account Number (IBAN) of the account.
     * [Required]
     * min 15 characters, max 34 characters
     * @var string
     */
    public $account_number;

    /**
     * The country of the account.
     * [Required]
     * min 2 characters, max 2 characters
     * @var string values of Checkout\Common\Country
     */
    public $country;

    /**
     * The currency of the account.
     * [Required]
     * min 3 characters, max 3 characters
     * @var string values of Checkout\Common\Currency
     */
    public $currency;

    /**
     * The type of payment. recurring or regular.
     * [Required]
     * @var string values of Checkout\Instruments\SepaPaymentType
     */
    public $payment_type;

    /**
     * The mandate ID. If a mandate ID is not provided, a new, random mandate ID will be generated.
     * [Optional]
     * min 1 characters, max 35 characters
     * @var string
     */
    public $mandate_id;

    /**
     * The date on which the mandate was signed.
     * Required if mandate_id is provided. Ignored and set as the current date if mandate_id is not
     * provided.
     * [Optional]
     * Format: yyyy-MM-dd
     * @var string
     */
    public $date_of_signature;
}
