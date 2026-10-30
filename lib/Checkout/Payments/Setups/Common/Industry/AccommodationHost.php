<?php

namespace Checkout\Payments\Setups\Common\Industry;

use Checkout\Common\EmptyAsObject;
use DateTime;
use Checkout\Common\DateOnly;

#[EmptyAsObject]
class AccommodationHost
{
    /**
     * The date the host registered as an accommodation provider.
     * Format: yyyy-MM-dd
     * [Optional]
     * @var DateTime
     */
    #[DateOnly]
    public $registration_date;

    /**
     * The total number of reservations the host has received.
     * [Optional]
     * @var int
     */
    public $total_reservation_count;
}
