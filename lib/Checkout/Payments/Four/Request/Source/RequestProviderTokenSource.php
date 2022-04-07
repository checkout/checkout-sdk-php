<?php

namespace Checkout\Payments\Four\Request\Source;

use Checkout\Common\PaymentSourceType;

class RequestProviderTokenSource extends AbstractRequestSource
{

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$provider_token);
    }

    public $payment_method;

    public $token;

    // AccountHolder
    public $account_holder;
}
