<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\TrueMoney;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentSetupPaymentMethod;

/**
 * The TrueMoney payment method's details and configuration.
 *
 * Inherits status and flags from PaymentSetupPaymentMethod.
 */
class TrueMoney extends PaymentSetupPaymentMethod
{
    /**
     * The client-side terminal type. Indicates whether the customer is using a PC browser, mobile browser,
     * or mobile application.
     * [Optional]
     * @var string value of PaymentSetupTerminalType
     */
    public $terminal_type;

    /**
     * The customer's operating system type. Required when terminal_type is not web.
     * [Optional]
     * @var string value of PaymentSetupOsType
     */
    public $os_type;
}
