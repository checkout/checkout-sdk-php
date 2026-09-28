<?php

namespace Checkout\Payments;

/**
 * Contains information about a room booked by the customer.
 */
class AccommodationRoom
{
    /**
     * The room rate per night.
     * @var string
     */
    public $rate;

    /**
     * The number of nights at this room rate.
     * @var string
     */
    public $number_of_nights_at_room_rate;
}
