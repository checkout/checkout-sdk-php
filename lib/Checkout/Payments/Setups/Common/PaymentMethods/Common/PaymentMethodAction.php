<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Common;

use Checkout\Common\EmptyAsObject;

#[EmptyAsObject]
class PaymentMethodAction
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $client_token;

    /**
     * @var string
     */
    public $session_id;
}
