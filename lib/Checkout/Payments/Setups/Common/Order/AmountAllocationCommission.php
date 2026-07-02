<?php

namespace Checkout\Payments\Setups\Common\Order;

class AmountAllocationCommission
{
    /**
     * Optional fixed amount of commission to collect, in the minor currency unit.
     * [Optional]
     * >= 0
     * @var int
     */
    public $amount;

    /**
     * Optional percentage of commission to collect.
     * [Optional]
     * >= 0
     * <= 100
     * @var float
     */
    public $percentage;
}
