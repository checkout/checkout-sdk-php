<?php

namespace Checkout\Accounts;

/**
 * For SaaS sellers (ISV), a daily schedule runs on working days only (monday to friday);
 * payouts do not take place on weekends.
 */
class ScheduleFrequencyDailyRequest extends ScheduleRequest
{
    public function __construct()
    {
        parent::__construct(ScheduleFrequency::$DAILY);
    }
}
