<?php

namespace Checkout\Payments\Request\Source\Apm;

use Checkout\Common\Address;
use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Request\Source\AbstractRequestSource;

class RequestWeChatPaySource extends AbstractRequestSource
{
    /**
     * @var Address
     */
    public $billing_address;

    public function __construct()
    {
        parent::__construct(PaymentSourceType::$wechatpay);
    }
}
