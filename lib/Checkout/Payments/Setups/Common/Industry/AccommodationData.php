<?php

namespace Checkout\Payments\Setups\Common\Industry;

use DateTime;
use Checkout\Common\DateOnly;
use Checkout\Payments\AccommodationGuest;

/**
 * Details about the accommodation the customer booked, for lodging or cruise bookings.
 */
class AccommodationData
{
    /**
     * For lodging, contains the lodging name that appears on the storefront/customer receipts.
     * For cruise, contains the ship name booked for the cruise.
     * [Optional]
     * @var string
     */
    public $name;

    /**
     * A unique identifier for the booking.
     * [Optional]
     * @var string
     */
    public $booking_reference;

    /**
     * For lodging bookings, the customer's check-in date.
     * For cruise bookings, the cruise departure date (sail date).
     * Format: yyyy-MM-dd
     * [Optional]
     * @var DateTime
     */
    #[DateOnly]
    public $check_in_date;

    /**
     * For lodging bookings, the customer's check-out date.
     * For cruise bookings, the cruise return date.
     * Format: yyyy-MM-dd
     * [Optional]
     * @var DateTime
     */
    #[DateOnly]
    public $check_out_date;

    /**
     * The accommodation's address.
     * [Optional]
     *
     * Uses the setups-local PaymentSetupAccommodationAddress: this schema declares city, state
     * and country, which the payments AccommodationAddress does not have.
     *
     * @var PaymentSetupAccommodationAddress
     */
    public $address;

    /**
     * The total number of rooms booked for the accommodation.
     * [Optional]
     * @var int
     */
    public $number_of_rooms;

    /**
     * The list of guests staying at the accommodation.
     * [Optional]
     * @var AccommodationGuest[]
     */
    public $guests;

    /**
     * The list of rooms booked by the customer.
     * [Optional]
     *
     * Uses the setups-local PaymentSetupAccommodationRoom: this schema declares
     * number_of_nights and type, where the payments AccommodationRoom declares
     * number_of_nights_at_room_rate and no type.
     *
     * @var PaymentSetupAccommodationRoom[]
     */
    public $room;

    /**
     * The total number of guests on the booking.
     * [Optional]
     * @var int
     */
    public $total_number_of_guests;

    /**
     * Specifies whether the booking is refundable.
     * [Optional]
     * @var bool
     */
    public $refundable;

    /**
     * The recipient the booking confirmation is delivered to.
     * [Optional]
     * @var string
     */
    public $delivery_recipient;

    /**
     * Details about the host of the accommodation.
     * [Optional]
     * @var AccommodationHost
     */
    public $host;
}
