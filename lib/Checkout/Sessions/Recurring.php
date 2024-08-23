<?php

namespace Checkout\Sessions;

class Recurring
{
    /**
     * @var int
     */
    public $days_between_payments = 1;

    /**
     * @var string
     */
    public $expiry = "99991231";
}
