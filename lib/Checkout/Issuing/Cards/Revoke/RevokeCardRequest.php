<?php

namespace Checkout\Issuing\Cards\Revoke;

class RevokeCardRequest
{
    /**
     * The reason why the card is being revoked.
     * [Optional]
     * Enum: expired, reported_lost, reported_stolen (see RevokeReason).
     *
     * @var string|null value of RevokeReason
     */
    public $reason;
}
