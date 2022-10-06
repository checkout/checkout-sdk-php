<?php

namespace Checkout\Payments\Request\Source;

use Checkout\Common\PaymentSourceType;

class RequestCustomerSource extends AbstractRequestSource
{

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$customer);
    }

    /**
     * @var string
     */
    public $id;
}
