<?php

namespace Checkout\Sessions;

class Installment
{
    /**
     * @var int
     */
    public $number_of_payments;

    /**
     * @var int
     */
    public $days_between_payments;

    /**
     * @var string
     */
    public $expiry;
}
