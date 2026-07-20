<?php

namespace Checkout\Accounts;

class ProcessingDetailsAch
{
    /**
     * @var int the estimated annual ACH processing volume in minor units without decimals
     */
    public $annual_ach_volume;

    /**
     * @var int the estimated average ACH transaction size in minor units without decimals
     */
    public $average_ach_transaction_size;

    /**
     * @var int the estimated monthly credit volume in minor units without decimals
     */
    public $estimated_monthly_credit_volume;

    /**
     * @var int the estimated average credit amount in minor units without decimals
     */
    public $average_credit_amount;
}
