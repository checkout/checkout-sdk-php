<?php

namespace Checkout\Payments\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Source\AbstractRequestSource;

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
