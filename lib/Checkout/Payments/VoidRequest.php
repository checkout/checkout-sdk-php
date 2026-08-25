<?php

namespace Checkout\Payments;

class VoidRequest
{
    /**
     * The amount to void. If not specified, the full payment amount is voided.
     * Min 0, max 9999999999.
     * @var int
     */
    public $amount;

    /**
     * @var string
     */
    public $reference;

    /**
     * @var array
     */
    public $metadata;
}
