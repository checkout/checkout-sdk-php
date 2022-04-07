<?php

namespace Checkout\Reconciliation;

use Checkout\Common\QueryFilterDateRange;

class ReconciliationQueryPaymentsFilter extends QueryFilterDateRange
{
    public $limit;

    public $reference;
}
