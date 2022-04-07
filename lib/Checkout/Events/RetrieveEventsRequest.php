<?php

namespace Checkout\Events;

use Checkout\Common\QueryFilterDateRange;

class RetrieveEventsRequest extends QueryFilterDateRange
{
    public $payment_id;

    public $charge_id;

    public $track_id;

    public $reference;

    public $skip;

    public $limit;
}
