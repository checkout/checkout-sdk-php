<?php

namespace Checkout\Instruments\Update;

/**
 * The details of the Bacs Direct Debit account being updated.
 */
class UpdateBacsInstrumentData
{
    /**
     * The account number of the Bacs Direct Debit account.
     * [Optional]
     * min 8 characters, max 8 characters
     * @var string
     */
    public $account_number;

    /**
     * The sort code of the Bacs Direct Debit account.
     * [Optional]
     * min 6 characters, max 6 characters
     * @var string
     */
    public $bank_code;

    /**
     * The country of the account, as an ISO 3166-1 alpha-2 code.
     * [Optional]
     * min 2 characters, max 2 characters
     * @var string values of Checkout\Common\Country
     */
    public $country;

    /**
     * The currency of the account.
     * [Optional]
     * min 3 characters, max 3 characters
     * @var string values of Checkout\Common\Currency
     */
    public $currency;

    /**
     * The type of payment. Recurring or Regular.
     * [Optional]
     * @var string values of Checkout\Instruments\BacsPaymentType
     */
    public $payment_type;

    /**
     * Whether vault accepted a partial match when looking up the Bacs instrument for the supplied
     * account details.
     * [Optional]
     * @var bool
     */
    public $allow_partial_match;
}
