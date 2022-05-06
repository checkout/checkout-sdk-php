<?php

namespace Checkout\Payments\Source\Apm;

use Checkout\Common\Country;
use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Payer;
use Checkout\Payments\Source\AbstractRequestSource;

class RequestRapiPagoSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$rapipago);
        $this->integration_type = IntegrationType::$redirect;
    }

    /**
     * @var IntegrationType
     */
    public $integration_type;

    /**
     * @var Country
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
