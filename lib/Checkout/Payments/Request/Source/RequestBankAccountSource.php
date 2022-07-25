<?php

namespace Checkout\Payments\Request\Source;

use Checkout\Common\AccountHolder;
use Checkout\Common\PaymentSourceType;

class RequestBankAccountSource extends AbstractRequestSource
{

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$bank_account);
    }

    /**
     * @var string
     */
    public $payment_method;

    /**
     * @var string
     */
    public $account_type;

    /**
     * @var string values of Country
     */
    public $country;

    /**
     * @var string
     */
    public $account_number;

    /**
     * @var string
     */
    public $bank_code;

    /**
     * @var AccountHolder
     */
    public $account_holder;
}
