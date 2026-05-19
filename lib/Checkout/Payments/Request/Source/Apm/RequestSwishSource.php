<?php

namespace Checkout\Payments\Request\Source\Apm;

use Checkout\Common\AccountHolder;
use Checkout\Common\PaymentSourceType;
use Checkout\Payments\BillingDescriptor;
use Checkout\Payments\Request\Source\AbstractRequestSource;

class RequestSwishSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$swish);
    }

    /**
     * @var string values of Country
     */
    public $payment_country;

    /**
     * @var AccountHolder
     */
    public $account_holder;

    /**
     * @var BillingDescriptor
     */
    public $billing_descriptor;
}
