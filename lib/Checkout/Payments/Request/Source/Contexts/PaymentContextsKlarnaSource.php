<?php

namespace Checkout\Payments\Request\Source\Contexts;

use Checkout\Common\AccountHolder;
use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Request\Source\AbstractRequestSource;

class PaymentContextsKlarnaSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$klarna);
    }

    /**
     * @var AccountHolder
     */
    public $account_holder;
}
