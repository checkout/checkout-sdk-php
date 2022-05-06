<?php

namespace Checkout\Payments\Source\Apm;

use Checkout\Common\Country;
use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Source\AbstractRequestSource;

class RequestP24Source extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$przelewy24);
    }

    /**
     * @var Country
     */
    public $payment_country;

    /**
     * @var string
     */
    public $account_holder_name;

    /**
     * @var string
     */
    public $account_holder_email;

    /**
     * @var string
     */
    public $billing_descriptor;
}
