<?php

namespace Checkout\Accounts;

use Checkout\Common\AbstractQueryFilter;

class PaymentInstrumentsQuery extends AbstractQueryFilter
{
    /**
     * @var string
     */
    public $status;
}
