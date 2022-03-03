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

    public string $description;

    public string $customer_mobile;

    public string $customer_email;

    // FawryProduct
    public array $products;

}
