<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\PayByBank;

use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodBase;

class PayByBank extends PaymentMethodBase
{
    /**
     * The identifier of the bank the customer has selected for the payment.
     * [Optional]
     * @var string
     */
    public $bank_id;

    /**
     * The next available action for the payment method (response only).
     * [Optional] readOnly
     * @var PayByBankAction
     */
    public $action;
}
