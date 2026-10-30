<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Klarna;

use Checkout\Common\EmptyAsObject;

#[EmptyAsObject]
class KlarnaAccountHolder
{
    /**
     * The full name of the account holder.
     * [Optional] readOnly
     * @var string
     */
    public $name;
}
