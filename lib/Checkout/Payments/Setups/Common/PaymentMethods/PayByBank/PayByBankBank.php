<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\PayByBank;

class PayByBankBank
{
    /**
     * The unique identifier of the bank.
     * [Optional]
     * @var string
     */
    public $bank_id;

    /**
     * The display name of the bank.
     * [Optional]
     * @var string
     */
    public $display_name;

    /**
     * The URL of the bank's logo.
     * [Optional]
     * @var string
     */
    public $logo_url;

    /**
     * Whether the bank is currently available for selection.
     * [Optional]
     * @var bool
     */
    public $available;
}
