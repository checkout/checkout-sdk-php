<?php

namespace Checkout\Marketplace;

class ScheduleFrequencyWeeklyRequest extends ScheduleRequest
{
    /**
     * @var DaySchedule
     */
    public $by_day;

    public function __construct()
    {
        parent::__construct(ScheduleFrequency::$WEEKLY);
    }
}
