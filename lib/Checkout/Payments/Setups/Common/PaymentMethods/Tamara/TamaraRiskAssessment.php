<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Tamara;

use Checkout\Common\EmptyAsObject;

/**
 * Risk signals about the customer, forwarded to Tamara with the payment.
 */
#[EmptyAsObject]
class TamaraRiskAssessment
{
    /**
     * Specifies whether the customer is a premium customer.
     * [Optional]
     * @var bool
     */
    public $premium_customer;

    /**
     * The customer's risk score.
     * [Optional]
     * @var int
     */
    public $risk_score;

    /**
     * The number of orders the customer has made in the last 24 hours.
     * [Optional]
     * @var int
     */
    public $order_count_last24h;

    /**
     * The number of devices linked to the customer.
     * [Optional]
     * @var int
     */
    public $linked_device_count;
}
