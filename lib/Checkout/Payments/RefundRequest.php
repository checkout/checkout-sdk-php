<?php

namespace Checkout\Payments;

use Checkout\Common\Destination;

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

    /**
     * @var string
     */
    public $capture_action_id;

    /**
     * @var Destination
     */
    public $destination;

    /**
     * @var array values of Order
     */
    public $items;
}
