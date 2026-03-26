<?php

namespace Checkout\Issuing\Testing;

class SimulateRefundRequest
{
    /**
     * The amount to refund. If not specified, the full cleared amount will be refunded.
     * You must provide the amount in the transaction currency's minor currency units. (Required)
     *
     * @var int
     */
    public $amount;
}
