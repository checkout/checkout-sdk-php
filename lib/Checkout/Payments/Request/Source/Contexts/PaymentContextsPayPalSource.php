<?php

namespace Checkout\Payments\Request\Source\Contexts;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Request\Source\AbstractRequestSource;

class PaymentContextsPayPalSource extends AbstractRequestSource
{

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$paypal);
    }

}
