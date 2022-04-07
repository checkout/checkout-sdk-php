<?php

namespace Checkout\Disputes;

use Checkout\Common\QueryFilterDateRange;

class DisputesQueryFilter extends QueryFilterDateRange
{
    public $limit;

    public $skip;

    public $id;

    public $statuses;

    public $payment_id;

    public $payment_reference;

    public $payment_arn;

    public $this_channel_only;

    //Fields only for CS2

    public $entity_ids;

    public $sub_entity_ids;

    public $payment_mcc;
}
