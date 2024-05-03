<?php

namespace Checkout\Payments\Request\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\BillingPlan;
use Checkout\Payments\Request\Source\AbstractRequestSource;

class RequestPayPalSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$paypal);
    }

    /**
     * @var BillingPlan
     */
    public $plan;
}
