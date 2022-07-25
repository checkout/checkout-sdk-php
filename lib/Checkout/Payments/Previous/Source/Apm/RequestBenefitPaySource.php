<?php

namespace Checkout\Payments\Previous\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Previous\Source\AbstractRequestSource;

class RequestBenefitPaySource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$benefitpay);
    }

    /**
     * @var string
     */
    public $integration_type;
}
