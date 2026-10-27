<?php

namespace Checkout\Payments\Setups\Common\Risk;

use Checkout\Common\EmptyAsObject;

#[EmptyAsObject]
class PaymentSetupRisk
{
    /**
     * Indicates whether a risk assessment should be performed. When omitted, the API applies its default
     * of true.
     * [Optional]
     * Default: true
     * @var bool
     */
    public $enabled;
}
