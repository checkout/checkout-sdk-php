<?php

namespace Checkout\Payments\Request\Source;

use Checkout\Common\AccountHolder;
use Checkout\Common\PaymentSourceType;

class RequestProviderTokenSource extends AbstractRequestSource
{

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$provider_token);
    }

    /**
     * @var string
     */
    public $payment_method;

    /**
     * @var string
     */
    public $token;

    /**
     * @var AccountHolder
     */
    public $account_holder;
}
