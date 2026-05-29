<?php

namespace Checkout\Payments\Request\Source;

use Checkout\Common\AccountHolder;
use Checkout\Common\Address;
use Checkout\Common\PaymentSourceType;
use Checkout\Common\Phone;

class RequestTokenSource extends AbstractRequestSource
{

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$token);
    }

    /**
     * @var string
     */
    public $token;

    /**
     * @var Address
     */
    public $billing_address;

    /**
     * @var Phone
     */
    public $phone;

    /**
     * This must be set to true if you intend to reuse the payment credentials in subsequent payments.
     * [Optional]
     * default true
     * @var bool|null $store_for_future_use
     */
    public $store_for_future_use;

    /**
     * Information about the account holder of the card.
     * [Optional]
     * @var AccountHolder|null $account_holder
     */
    public $account_holder;
}
