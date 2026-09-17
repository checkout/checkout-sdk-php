<?php

namespace Checkout\Payments\Setups\Common\Industry;

use Checkout\Payments\Ticket;

class AirlineData
{
    /**
     * Details about the airline ticket.
     * [Optional]
     * @var Ticket
     */
    public $ticket;

    /**
     * The list of passengers on the flight.
     * [Optional]
     * @var array of Passenger
     */
    public $passengers;

    /**
     * The list of flight legs booked by the customer.
     * [Optional]
     * @var array of FlightLegDetails
     */
    public $flight_leg_details;

    /**
     * The total number of passengers on the booking.
     * [Optional]
     * @var int
     */
    public $total_number_of_passengers;

    /**
     * The type of travel, for example "international" or "domestic".
     * [Optional]
     * @var string
     */
    public $travel_type;

    /**
     * The type of trip, for example "one_way" or "round_trip".
     * [Optional]
     * @var string
     */
    public $trip_type;

    /**
     * Specifies whether the booking is refundable.
     * [Optional]
     * @var bool
     */
    public $refundable;

    /**
     * The recipient the ticket is delivered to.
     * [Optional]
     * @var string
     */
    public $delivery_recipient;

    /**
     * Any additional add-ons purchased with the booking, for example "extra_baggage".
     * [Optional]
     * @var string
     */
    public $ancillaries;

    /**
     * Details about the travel insurance purchased with the booking.
     * [Optional]
     * @var AirlineInsurance
     */
    public $insurance;
}
