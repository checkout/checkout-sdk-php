<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Klarna;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodAction;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodBase;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodOptions;

/**
 * The Klarna payment method's details and configuration.
 *
 * Inherits status and flags from PaymentSetupPaymentMethod and initialization from PaymentMethodBase.
 */
class Klarna extends PaymentMethodBase
{
    /**
     * The next available action for the payment method. For Klarna the type is "sdk", with the
     * client_token and session_id to use with the Klarna SDK.
     * [Optional]
     * readOnly
     * @var PaymentMethodAction
     */
    public $action;

    /**
     * The account holder details returned by Klarna after the shopper completes verification.
     * [Optional]
     * readOnly
     * @var KlarnaAccountHolder
     */
    public $account_holder;

    /**
     * The IBAN of the account used to complete Klarna verification.
     * [Optional]
     * readOnly
     * @var string
     */
    public $iban;

    /**
     * @var PaymentMethodOptions
     */
    public $payment_method_options;
}
