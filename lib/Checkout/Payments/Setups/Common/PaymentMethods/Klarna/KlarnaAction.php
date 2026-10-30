<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Klarna;

use Checkout\Common\EmptyAsObject;

/**
 * The next available action for the Klarna payment method (response only).
 */
#[EmptyAsObject]
class KlarnaAction
{
    /**
     * The type of action.
     * [Optional]
     * readOnly
     * Enum: "sdk"
     * @var string value of KlarnaActionType
     */
    public $type;

    /**
     * The client token for the Klarna SDK.
     * [Optional]
     * readOnly
     * @var string
     */
    public $client_token;

    /**
     * The session ID.
     * [Optional]
     * readOnly
     * @var string
     */
    public $session_id;
}
