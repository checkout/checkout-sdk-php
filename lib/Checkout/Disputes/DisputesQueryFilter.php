<?php

namespace Checkout\Disputes;

use Checkout\Common\AbstractQueryFilter;
use DateTime;

class DisputesQueryFilter extends AbstractQueryFilter
{
    public int $limit;

    public int $skip;

    public DateTime $from;

    public DateTime $to;

    public string $id;

    public string $statuses;

    public string $payment_id;

    public string $payment_reference;

    public string $payment_arn;

    public string $this_channel_only;

    //Fields only for CS2

    public string $entity_ids;

    public string $sub_entity_ids;

    public string $payment_mcc;

}
