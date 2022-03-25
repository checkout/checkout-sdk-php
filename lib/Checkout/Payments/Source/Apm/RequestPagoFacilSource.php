<?php

namespace Checkout\Payments\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Source\AbstractRequestSource;

class RequestPagoFacilSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$pagofacil);
        $this->integration_type = IntegrationType::$redirect;
    }

    public $integration_type;

    public $country;

    // Payer
    public $payer;

    public $description;

}
