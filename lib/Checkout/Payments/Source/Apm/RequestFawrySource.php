<?php

namespace Checkout\Payments\Source\Apm;

use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Source\AbstractRequestSource;

class RequestFawrySource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$fawry);
    }

    public $description;

    public $customer_mobile;

    public $customer_email;

    // FawryProduct
    public $products;

}
