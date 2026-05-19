<?php

namespace Checkout\Payments\Request\Source\Apm;

use Checkout\Common\AccountHolderAch;
use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Request\Source\AbstractRequestSource;

class RequestAchSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$ach);
    }

    /**
     * @var string value of AccountType (savings, checking, cash)
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
     * @var AccountHolderAch
     */
    public $account_holder;
}
