<?php

namespace Checkout\Payments\Request;

class PaymentRouting
{
    /**
     * Specifies the processing rules for the payment. Each object in the array
     * should be a unique expression of rules that determine the routing attempts
     * to process the payment with.
     * [Optional]
     * @var PaymentRoutingAttempt[] $attempts
     */
    public $attempts;
}
