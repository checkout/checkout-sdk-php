<?php

namespace Checkout\Marketplace;

class UpdateScheduleRequest
{
    /**
     * @var bool
     */
    public $enabled;

    /**
     * @var int
     */
    public $threshold;

    /**
     * @var ScheduleRequest
     */
    public $recurrence;
}
