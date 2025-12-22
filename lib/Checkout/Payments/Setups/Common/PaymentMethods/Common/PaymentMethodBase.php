<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Common;

abstract class PaymentMethodBase
{
    /**
     * @var string
     */
    public $status;

    /**
     * @var string[]
     */
    public $flags;

    /**
     * @var string
     */
    public $initialization;
}
