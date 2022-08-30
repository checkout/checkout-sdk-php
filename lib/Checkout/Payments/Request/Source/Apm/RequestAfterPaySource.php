<?php

namespace Checkout\Payments\Request\Source\Apm;

use Checkout\Common\AccountHolder;
use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Request\Source\AbstractRequestSource;

class RequestAfterPaySource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$afterpay);
    }

    /**
     * @var AccountHolder
     */
    public $account_holder;
}
