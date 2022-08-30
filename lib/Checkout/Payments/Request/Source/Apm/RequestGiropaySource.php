<?php

namespace Checkout\Payments\Request\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Request\Source\AbstractRequestSource;

class RequestGiropaySource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$giropay);
    }

    /**
     * @var string
     */
    public $purpose;

    /**
     * @var array
     */
    public $info_fields;
}
