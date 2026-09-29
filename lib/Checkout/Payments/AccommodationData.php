<?php

namespace Checkout\Payments;

use DateTime;
use Checkout\Common\DateOnly;

/**
 * Contains information about the accommodation booked by the customer.
 */
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
     * Format: yyyy-MM-dd
     * @var DateTime
     */
    #[DateOnly]
    public $check_in_date;

    /**
     * The check-out date.
     * Format: yyyy-MM-dd
     * @var DateTime
     */
    #[DateOnly]
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
     * @var AccommodationGuest[]
     */
    public $guests;

    /**
     * The room details and rates.
     * [Optional]
     *
     * @var AccommodationRoom[]
     */
    public $room;

    /**
     * The property's phone information.
     * [Optional]
     *
     * @var AccommodationPhone[]
     */
    public $property_phone;

    /**
     * The customer service phone information.
     * [Optional]
     *
     * @var AccommodationPhone[]
     */
    public $customer_service_phone;
}
