<?php

namespace Checkout\Payments\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Source\AbstractRequestSource;

class RequestRapiPagoSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$rapipago);
        $this->integration_type = IntegrationType::$redirect;
    }

    public $integration_type;

    public $country;

    // Payer
    public $payer;

    public $description;

}
