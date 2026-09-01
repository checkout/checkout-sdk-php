<?php

namespace Checkout\Instruments\Create;

/**
 * The details of the Bacs Direct Debit account being stored.
 */
class CreateBacsInstrumentData
{
    /**
     * The account number of the Bacs Direct Debit account.
     * [Required]
     * min 8 characters, max 8 characters
     * @var string
     */
    public $account_number;

    /**
     * The sort code of the Bacs Direct Debit account.
     * [Required]
     * min 6 characters, max 6 characters
     * @var string
     */
    public $bank_code;

    /**
     * The country of the account, as an ISO 3166-1 alpha-2 code.
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
     * The type of payment. Recurring or Regular.
     * [Required]
     * @var string values of Checkout\Instruments\BacsPaymentType
     */
    public $payment_type;

    /**
     * Indicates whether the Bacs instrument is created when account validation returns a partial
     * match. When true, the instrument is created on a partial match; when false, instrument
     * creation fails on a partial match.
     * [Optional]
     * Default: false
     * @var bool
     */
    public $allow_partial_match;
}
