<?php

namespace Checkout\Payments\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Source\AbstractRequestSource;

class RequestBalotoSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$baloto);
        $this->integration_type = IntegrationType::$redirect;
    }

    public $integration_type;

    public $country;

    public $description;

    // BalotoPayer
    public $payer;

}
