<?php

namespace Checkout\Accounts;

class ScheduleFrequencyMonthlyRequest extends ScheduleRequest
{
    /**
     * @var array int
     */
    public $by_month_day;

    public function __construct()
    {
        parent::__construct(ScheduleFrequency::$MONTHLY);
    }
}
