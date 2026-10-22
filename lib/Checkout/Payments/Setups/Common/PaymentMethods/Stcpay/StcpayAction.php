<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Stcpay;

/**
 * The next available action for the stc pay payment method (response only).
 */
class StcpayAction
{
    /**
     * The type of action.
     * [Optional]
     * readOnly
     * Enum: "otp"
     * @var string value of StcpayActionType
     */
    public $type;
}
