<?php

namespace Checkout\Payments;

use DateTime;

class AccommodationData
{
    /**
     * The name of the hotel or accommodation.
     * @var string
     */
    public $name;

    /**
     * The booking reference number.
     * @var string
     */
    public $booking_reference;

    /**
     * The check-in date.
     * @var DateTime
     */
    public $check_in_date;

    /**
     * The check-out date.
     * @var DateTime
     */
    public $check_out_date;

    /**
     * The accommodation address.
     * @var AccommodationAddress
     */
    public $address;

    /**
     * The state or province of the accommodation.
     * @var string
     */
    public $state;

    /**
     * The country of the accommodation.
     * @var string
     */
    public $country;

    /**
     * The city of the accommodation.
     * @var string
     */
    public $city;

    /**
     * The number of rooms booked.
     * @var int
     */
    public $number_of_rooms;

    /**
     * The list of guests staying at the accommodation.
     * @var array of AccommodationGuest
     */
    public $guests;

    /**
     * The room details and rates.
     * @var array of AccommodationRoom
     */
    public $room;
}
