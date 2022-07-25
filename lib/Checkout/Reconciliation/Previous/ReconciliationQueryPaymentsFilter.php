<?php

namespace Checkout\Reconciliation\Previous;

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
