<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Ach;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentSetupPaymentMethod;

/**
 * The ACH payment method's details and configuration.
 *
 * Inherits status and flags from PaymentSetupPaymentMethod.
 */
class Ach extends PaymentSetupPaymentMethod
{
    /**
     * The type of Direct Debit account.
     * [Optional]
     * @var string value of PaymentSetupAchAccountType
     */
    public $account_type;

    /**
     * The account holder details.
     * [Optional]
     * @var AchAccountHolder
     */
    public $account_holder;

    /**
     * The account number of the Direct Debit account.
     * [Optional]
     * writeOnly
     * min 4 characters, max 17 characters
     * @var string
     */
    public $account_number;

    /**
     * The bank code of the Direct Debit account.
     * [Optional]
     * writeOnly
     * min 8 characters, max 9 characters
     * @var string
     */
    public $bank_code;

    /**
     * The two-letter ISO country code of the bank account.
     * [Optional]
     * min 2 characters, max 2 characters
     * @var string value of Country
     */
    public $country;
}
