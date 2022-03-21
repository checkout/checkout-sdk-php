<?php

namespace Checkout\Disputes;

use Checkout\Common\AbstractQueryFilter;

class DisputesQueryFilter extends AbstractQueryFilter
{
    public $limit;

    public $skip;

    // DateTime
    public $from;

    // DateTime
    public $to;

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
