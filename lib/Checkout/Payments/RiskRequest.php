<?php

namespace Checkout\Payments;

class RiskRequest
{
    /**
     * @var bool
     */
    public $enabled;

    /**
     * @var string
     */
    public $device_session_id;
}
