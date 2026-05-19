<?php

namespace Checkout\Payments\Request\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Request\Source\AbstractRequestSource;

class RequestBlikSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$blik);
    }

    /**
     * @var string
     */
    public $partner_agreement_id;
}
