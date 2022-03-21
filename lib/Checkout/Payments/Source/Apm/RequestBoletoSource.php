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

    public $integration_type;

    public $country;

    public $description;

    // Payer
    public $payer;
}
