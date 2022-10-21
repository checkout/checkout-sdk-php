<?php

namespace Checkout\Reports;

use Checkout\Common\AbstractQueryFilter;
use DateTime;

class ReportsQuery extends AbstractQueryFilter
{
    /**
     * @var DateTime
     */
    public $created_after;

    /**
     * @var DateTime
     */
    public $created_before;

    /**
     * @var string
     */
    public $entity_id;

    /**
     * @var int
     */
    public $limit;

    /**
     * @var string
     */
    public $pagination_token;
}
