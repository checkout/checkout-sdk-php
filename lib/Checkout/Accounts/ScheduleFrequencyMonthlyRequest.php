<?php

namespace Checkout\Accounts;

class ScheduleFrequencyMonthlyRequest extends ScheduleRequest
{
    /**
     * The day or days of the month the payout should take place.
     * For SaaS sellers (ISV), only the following combinations are supported,
     * in any order: [1], [15], [1, 15] or [1, 16].
     *
     * @var array int
     */
    public $by_month_day;

    public function __construct()
    {
        parent::__construct(ScheduleFrequency::$MONTHLY);
    }
}
