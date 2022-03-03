<?php

namespace Checkout\Payments\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Source\AbstractRequestSource;

class RequestBoletoSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$boleto);
        $this->integration_type = IntegrationType::$redirect;
    }

    public string $integration_type;

    public string $country;

    public string $description;

    public Payer $payer;
}
