<?php

namespace Checkout\Payments;

/**
 * Contains information about the airline ticket and flights booked by the customer.
 */
class AirlineData
{
    /**
     * Contains information about the airline ticket.
     * [Optional]
     *
     * @var Ticket
     */
    public $ticket;

    /**
     * Contains information about the passenger(s) on the flight.
     * [Optional]
     *
     * Assign a single Passenger for one passenger, not a one-element array. Verified against the
     * sandbox on 2026-09-25 with a complete airline_data block:
     *
     *   surface                  passenger: object   passenger: array
     *   POST /payments           201                 201
     *   POST /hosted-payments    accepted            422 processing_airline_data_0_passenger_invalid
     *   POST /payment-links      accepted            422 processing_airline_data_0_passenger_invalid
     *   POST /payment-contexts   201                 422 passenger_required
     *
     * A single object is accepted on every request surface; an array only on POST /payments.
     * The specification declares the opposite, and ProcessingSettings is shared by POST /payments,
     * hosted payments and payment links, so an array is not a safe default. An empty array and a
     * null are both rejected, so leave the property unset when there are no passengers: the
     * serializer omits nulls.
     *
     * Several passengers can only be expressed as an array, which only POST /payments accepts.
     * That is an API limitation, not an SDK choice. Recorded in the plan under P1.
     *
     * @var Passenger|Passenger[]
     */
    public $passenger;

    /**
     * Contains information about the flight leg(s) booked by the customer.
     * [Optional]
     *
     * @var FlightLegDetails[]
     */
    public $flight_leg_details;
}
