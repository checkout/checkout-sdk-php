<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Tamara;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodBase;

/**
 * The Tamara payment method's details and configuration.
 *
 * Inherits status and flags from PaymentSetupPaymentMethod and initialization from PaymentMethodBase.
 */
class Tamara extends PaymentMethodBase
{
    /**
     * The initialization state of the payment method. When you create a Payment Setup, this defaults to
     * disabled. On update the API replaces the payment methods, so send "enabled" again to keep the method
     * enabled. The specification does not state this; it is the API's behaviour.
     * [Optional]
     * Default: "disabled"
     * @var string value of PaymentMethodInitialization
     */
    public $initialization;

    /**
     * Risk signals about the customer, forwarded to Tamara with the payment.
     * [Optional]
     * @var TamaraRiskAssessment
     */
    public $risk_assessment;
}
