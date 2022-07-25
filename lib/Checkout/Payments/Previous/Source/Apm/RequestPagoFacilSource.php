<?php

namespace Checkout\Payments\Previous\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Payer;
use Checkout\Payments\Previous\Source\AbstractRequestSource;

class RequestPagoFacilSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$pagofacil);
        $this->integration_type = IntegrationType::$redirect;
    }

    /**
     * @var string value of IntegrationType
     */
    public $integration_type;

    /**
     * @var string values of Country
     */
    public $country;

    /**
     * @var Payer
     */
    public $payer;

    /**
     * @var string
     */
    public $description;
}
