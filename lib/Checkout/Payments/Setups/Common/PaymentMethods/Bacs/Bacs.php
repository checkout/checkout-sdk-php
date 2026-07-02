<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Bacs;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodBase;

class Bacs extends PaymentMethodBase
{
    /**
     * The ID of the Bacs instrument used for the payment.
     * [Optional] readOnly
     * @var string
     */
    public $instrument_id;

    /**
     * The account holder details.
     * [Optional]
     * @var BacsAccountHolder
     */
    public $account_holder;

    /**
     * The account number of the Bacs Direct Debit account.
     * [Optional] writeOnly
     * @var string
     */
    public $account_number;

    /**
     * The sort code of the Bacs Direct Debit account.
     * [Optional] writeOnly
     * @var string
     */
    public $bank_code;

    /**
     * The account's country, as an ISO 3166-1 alpha-2 code.
     * [Optional]
     * min 2 characters, max 2 characters
     * @var string value of Country
     */
    public $country;

    /**
     * The account holder's account currency.
     * [Optional]
     * @var string
     */
    public $currency;

    /**
     * Whether vault may accept a partial match when looking up an existing Bacs instrument for the supplied
     * account details.
     * [Optional]
     * Default: false
     * @var bool
     */
    public $allow_partial_match;
}
