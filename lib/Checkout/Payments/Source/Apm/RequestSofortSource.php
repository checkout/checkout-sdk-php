<?php

namespace Checkout\Payments\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Source\AbstractRequestSource;

class RequestSofortSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$sofort);
    }

    /**
     * @var string values of Country
     */
    public $countryCode;

    /**
     * @var string
     */
    public $languageCode;
}
