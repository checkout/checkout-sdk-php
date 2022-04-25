<?php

namespace Checkout\Payments\Four\Request\Source;

use Checkout\Common\Four\AccountHolder;
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
