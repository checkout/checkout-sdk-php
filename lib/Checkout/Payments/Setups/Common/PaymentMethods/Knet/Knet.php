<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Knet;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentSetupPaymentMethod;

/**
 * The KNET payment method's details and configuration.
 *
 * Inherits status and flags from PaymentSetupPaymentMethod.
 */
class Knet extends PaymentSetupPaymentMethod
{
    /**
     * The customer's preferred language, as an ISO 639-1 code. For example, the language selected on your
     * site, if the issuer's site supports it.
     * The specification marks this optional, but the API keeps the method invalid until it is sent.
     * [Optional]
     * @var string value of KnetLanguage
     */
    public $language;
}
