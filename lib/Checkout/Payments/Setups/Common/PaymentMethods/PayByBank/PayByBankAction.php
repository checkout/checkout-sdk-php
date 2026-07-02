<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\PayByBank;

class PayByBankAction
{
    /**
     * The type of action.
     * [Optional] readOnly
     * Enum: "select_bank"
     * @var string
     */
    public $type;

    /**
     * The list of banks available for the customer to select.
     * [Optional] readOnly
     * @var array of PayByBankBank
     */
    public $banks;
}
