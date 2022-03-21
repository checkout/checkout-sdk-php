<?php

namespace Checkout\Events;

use Checkout\Common\AbstractQueryFilter;

class RetrieveEventsRequest extends AbstractQueryFilter
{
    public $payment_id;

    public $charge_id;

    public $track_id;

    public $reference;

    public $skip;

    public $limit;

    // DateTime
    public $from;

    // DateTime
    public $to;
}
