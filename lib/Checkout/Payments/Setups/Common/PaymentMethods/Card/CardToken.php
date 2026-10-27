<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Card;

use Checkout\Common\EmptyAsObject;

/**
 * The Checkout.com card token for the Card payment method.
 */
#[EmptyAsObject]
class CardToken
{
    /**
     * The Checkout.com card token identifier.
     * [Optional]
     * @var string
     */
    public $id;
}
