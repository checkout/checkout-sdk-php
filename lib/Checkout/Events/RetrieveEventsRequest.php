<?php

namespace Checkout\Events;

use Checkout\Common\QueryFilterDateRange;

class RetrieveEventsRequest extends QueryFilterDateRange
{
    /**
     * @var string
     */
    public $payment_id;

    /**
     * @var string
     */
    public $charge_id;

    /**
     * @var string
     */
    public $track_id;

    /**
     * @var string
     */
    public $reference;

    /**
     * @var int
     */
    public $skip;

    /**
     * @var int
     */
    public $limit;
}
