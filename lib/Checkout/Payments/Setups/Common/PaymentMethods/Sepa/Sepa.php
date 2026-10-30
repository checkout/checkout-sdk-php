<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Sepa;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentSetupPaymentMethod;

/**
 * The SEPA payment method's details and configuration.
 *
 * Inherits status and flags from PaymentSetupPaymentMethod.
 */
class Sepa extends PaymentSetupPaymentMethod
{
    /**
     * The account holder details.
     * [Optional]
     * @var SepaAccountHolder
     */
    public $account_holder;

    /**
     * The account holder's IBAN.
     * [Optional]
     * writeOnly
     * @var string
     */
    public $account_number;

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
     * The mandate details.
     * [Optional]
     * @var SepaMandate
     */
    public $mandate;
}
