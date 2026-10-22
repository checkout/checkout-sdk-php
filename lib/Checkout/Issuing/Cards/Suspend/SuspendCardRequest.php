<?php

namespace Checkout\Issuing\Cards\Suspend;

class SuspendCardRequest
{
    /**
     * The reason why the card is being suspended.
     * [Optional]
     * Values: see SuspendReason.
     *
     * @var string|null value of SuspendReason
     */
    public $reason;
}
