<?php

namespace Checkout\Instruments\Update;

/**
 * The details of the ACH bank account being updated.
 *
 * The shape is identical across the store, update and retrieve variants, unlike the Bacs Direct
 * Debit instrument data whose length limits differ per operation.
 */
class UpdateAchInstrumentData
{
    /**
     * The type of Direct Debit account.
     * [Optional]
     * @var string values of Checkout\Instruments\AchAccountType
     */
    public $account_type;

    /**
     * The account number of the Direct Debit account.
     * [Optional]
     * min 4 characters, max 17 characters
     * @var string
     */
    public $account_number;

    /**
     * The bank code of the Direct Debit account, also known as the routing number.
     * [Optional]
     * min 8 characters, max 9 characters
     * @var string
     */
    public $bank_code;

    /**
     * The currency of the account.
     * [Optional]
     * min 3 characters, max 3 characters
     * @var string values of Checkout\Common\Currency
     */
    public $currency;

    /**
     * The country of the account.
     * [Optional]
     * min 2 characters, max 2 characters
     * @var string values of Checkout\Common\Country
     */
    public $country;
}
