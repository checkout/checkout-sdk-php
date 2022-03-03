<?php

namespace Checkout\Payments\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Source\AbstractRequestSource;

class RequestOxxoSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$oxxo);
        $this->integration_type = IntegrationType::$redirect;
    }

    public string $integration_type;

    public string $country;

    public Payer $payer;

    public string $description;

}
