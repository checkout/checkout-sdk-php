<?php

namespace Checkout\Disputes;

use Checkout\Common\QueryFilterDateRange;

class DisputesQueryFilter extends QueryFilterDateRange
{
    /**
     * @var int
     */
    public $limit;

    /**
     * @var int
     */
    public $skip;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $statuses;

    /**
     * @var string
     */
    public $payment_id;

    /**
     * @var string
     */
    public $payment_reference;

    /**
     * @var string
     */
    public $payment_arn;

    /**
     * @var string
     */
    public $this_channel_only;

    //Fields only for CS2

    /**
     * @var string
     */
    public $entity_ids;

    /**
     * @var string
     */
    public $sub_entity_ids;

    /**
     * @var string
     */
    public $payment_mcc;
}
