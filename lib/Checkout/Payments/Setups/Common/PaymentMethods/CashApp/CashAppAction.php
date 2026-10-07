<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\CashApp;

/**
 * The next available action for the Cash App payment method (response only).
 */
class CashAppAction
{
    /**
     * The type of action.
     * [Optional]
     * readOnly
     * Enum: "redirect"
     * @var string value of CashAppActionType
     */
    public $type;

    /**
     * The URL to redirect the customer to so they can authorize the payment with Cash App.
     * [Optional]
     * readOnly
     * Format: uri
     * @var string
     */
    public $redirect_url;
}
