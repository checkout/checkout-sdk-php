<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\CashApp;

/**
 * The type of the Cash App payment method action.
 */
class CashAppActionType
{
    /**
     * Redirect the customer to Cash App to authorize the payment.
     * @var string
     */
    public static $redirect = "redirect";
}
