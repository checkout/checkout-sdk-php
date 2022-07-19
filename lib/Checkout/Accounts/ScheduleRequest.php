<?php

namespace Checkout\Accounts;

abstract class ScheduleRequest
{
    /**
     * @var string value of ScheduleFrequency
     */
    public $frequency;

    /**
     * @param $frequency
     */
    public function __construct($frequency)
    {
        $this->frequency = $frequency;
    }
}
