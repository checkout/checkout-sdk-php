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

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $customer_mobile;

    /**
     * @var string
     */
    public $customer_email;

    /**
     * @var array of FawryProduct
     */
    public $products;
}
