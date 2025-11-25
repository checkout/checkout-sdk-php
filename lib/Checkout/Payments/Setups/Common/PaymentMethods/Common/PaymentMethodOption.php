<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Common;

class PaymentMethodOption
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string[]
     */
    public $flags;

    /**
    * @var PaymentMethodAction
     */
    public $action;
}
