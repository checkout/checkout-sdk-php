<?php

namespace Checkout\Issuing\Cards\Suspend;

class SuspendCardRequest
{
    /**
     * The reason why the card is being suspended.
     * [Optional]
     * Enum: suspected_lost, suspected_stolen (see SuspendReason).
     *
     * @var string|null value of SuspendReason
     */
    public $reason;
}
