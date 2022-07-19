<?php

namespace Checkout\Accounts;

class ScheduleFrequencyWeeklyRequest extends ScheduleRequest
{
    /**
     * @var array values of DaySchedule
     */
    public $by_day;

    public function __construct()
    {
        parent::__construct(ScheduleFrequency::$WEEKLY);
    }
}
