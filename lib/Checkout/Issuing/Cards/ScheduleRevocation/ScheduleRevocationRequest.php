<?php

namespace Checkout\Issuing\Cards\ScheduleRevocation;

class ScheduleRevocationRequest
{
    /**
     * Date for the card to be automatically revoked.
     * Must be after the current date and date only in the form yyyy-mm-dd. (Required)
     * @var string
     */
    public $revocation_date;
}
