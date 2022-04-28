<?php

namespace Checkout\Marketplace;

abstract class ScheduleRequest
{
    /**
     * @var ScheduleFrequency
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
