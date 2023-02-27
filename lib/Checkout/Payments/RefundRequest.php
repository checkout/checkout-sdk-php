<?php

namespace Checkout\Payments;

class RefundRequest
{
    /**
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

    //Not available on previous

    /**
     * @var array values of AmountAllocations
     */
    public $amount_allocations;
}
