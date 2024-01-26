<?php

namespace Checkout\Payments\Sessions;

use Checkout\Common\CustomerRequest;

class PaymentSessionsRequest
{
    /**
     * @var int
     */
    public $amount;

    /**
     * @var string value of Currency
     */
    public $currency;

    /**
     * @var string
     */
    public $reference;

    /**
     * @var Billing
     */
    public $billing;

    /**
     * @var CustomerRequest
     */
    public $customer;

    /**
     * @var string
     */
    public $success_url;

    /**
     * @var string
     */
    public $failure_url;
}
