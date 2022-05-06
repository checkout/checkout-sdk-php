<?php

namespace Checkout\Payments\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Source\AbstractRequestSource;

class RequestQPaySource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$qpay);
    }

    public $quantity;

    public $description;

    public $language;

    public $national_id;
}
