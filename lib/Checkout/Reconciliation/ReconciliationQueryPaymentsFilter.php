<?php

namespace Checkout\Reconciliation;

use Checkout\Common\QueryFilterDateRange;

class ReconciliationQueryPaymentsFilter extends QueryFilterDateRange
{
    /**
     * @var int
     */
    public $limit;

    /**
     * @var string
     */
    public $reference;
}
