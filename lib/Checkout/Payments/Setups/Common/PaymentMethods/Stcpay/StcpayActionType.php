<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Stcpay;

/**
 * The type of the stc pay payment method action.
 */
class StcpayActionType
{
    /**
     * Ask for the one-time password the customer received.
     * @var string
     */
    public static $otp = "otp";
}
