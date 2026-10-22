<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Klarna;

/**
 * The type of the Klarna payment method action.
 */
class KlarnaActionType
{
    /**
     * Complete the payment with the Klarna SDK, using the client_token and session_id.
     * @var string
     */
    public static $sdk = "sdk";
}
