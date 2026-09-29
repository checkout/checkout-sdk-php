<?php

namespace Checkout\Payments\Contexts;

/**
 * Contains information about the airline ticket and flights booked by the customer.
 */
class PaymentContextsAirlineData
{
    /**
     * Contains information about the airline ticket.
     * [Optional]
     *
     * The specification declares this as a single object. It was previously named $tickets and
     * documented as an array, so the SDK sent an array under the key "tickets", which the API
     * does not define: the value never reached the gateway.
     *
     * @var PaymentContextsTicket
     */
    public $ticket;

    /**
     * Contains information about the passenger(s) on the flight.
     * [Optional]
     *
     * It was previously named $passengers, so the SDK sent the key "passengers", which the API
     * does not define: the value never reached the gateway.
     *
     * Assign a single PaymentContextsPassenger, not an array. Verified against the sandbox on
     * 2026-09-25: POST /payment-contexts rejects the array form with 422 passenger_required and
     * accepts a single object. A single object is accepted on every request surface, while an
     * array is accepted only on POST /payments. This is the opposite of what the specification
     * declares, and is recorded in the plan under P1. An array of two or more is the only way to
     * express several passengers, and only POST /payments accepts it.
     *
     * @var PaymentContextsPassenger|PaymentContextsPassenger[]
     */
    public $passenger;

    /**
     * Contains information about the flight leg(s) booked by the customer.
     * [Optional]
     *
     * @var PaymentContextsFlightLegDetails[]
     */
    public $flight_leg_details;
}
