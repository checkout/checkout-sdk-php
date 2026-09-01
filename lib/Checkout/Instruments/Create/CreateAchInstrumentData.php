<?php

namespace Checkout\Instruments\Create;

/**
 * The details of the ACH bank account being stored.
 *
 * The shape is identical across the store, update and retrieve variants, unlike the Bacs Direct
 * Debit instrument data whose length limits differ per operation.
 */
class CreateAchInstrumentData
{
    /**
     * The type of Direct Debit account.
     * [Required]
     * @var string values of Checkout\Instruments\AchAccountType
     */
    public $account_type;

    /**
     * The account number of the Direct Debit account.
     * [Required]
     * min 4 characters, max 17 characters
     * @var string
     */
    public $account_number;

    /**
     * The bank code of the Direct Debit account, also known as the routing number.
     * [Required]
     * min 8 characters, max 9 characters
     * @var string
     */
    public $bank_code;

    /**
     * The currency of the account.
     * [Required]
     * min 3 characters, max 3 characters
     * @var string values of Checkout\Common\Currency
     */
    public $currency;

    /**
     * The country of the account.
     * [Required]
     * min 2 characters, max 2 characters
     * @var string values of Checkout\Common\Country
     */
    public $country;
}
