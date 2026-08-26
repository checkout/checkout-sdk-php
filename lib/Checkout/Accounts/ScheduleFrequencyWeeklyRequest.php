<?php

namespace Checkout\Accounts;

class ScheduleFrequencyWeeklyRequest extends ScheduleRequest
{
    /**
     * The day or days of the week the payout should take place.
     * For SaaS sellers (ISV), only working days (monday to friday) are supported;
     * payouts set to take place on weekends are rejected.
     *
     * @var array values of DaySchedule
     */
    public $by_day;

    public function __construct()
    {
        parent::__construct(ScheduleFrequency::$WEEKLY);
    }
}
