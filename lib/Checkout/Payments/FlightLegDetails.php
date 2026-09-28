<?php

namespace Checkout\Payments;

use DateTime;
use Checkout\Common\DateOnly;

/**
 * Contains information about a flight leg booked by the customer.
 */
class FlightLegDetails
{
    /**
     * The flight number.
     * @var string
     */
    public $flight_number;

    /**
     * The two-letter airline code.
     * @var string
     */
    public $carrier_code;

    /**
     * The service class of the flight.
     *
     * @deprecated Not in the current specification. Serializes as "service_class", which the API
     * does not define, so the gateway discards it. Use $class_of_travelling instead, which maps
     * the specification property class_of_travelling. Retained for backwards compatibility and
     * will be removed in a future version.
     * @var string
     */
    public $service_class;

    /**
     * The class of travel (e.g., J for Business).
     * @var string
     */
    public $class_of_travelling;

    /**
     * The departure date in YYYY-MM-DD format.
     * Format: yyyy-MM-dd
     * @var DateTime
     */
    #[DateOnly]
    public $departure_date;

    /**
     * The departure time in HH:MM format.
     * @var string
     */
    public $departure_time;

    /**
     * The three-letter IATA airport code for departure.
     * @var string
     */
    public $departure_airport;

    /**
     * The three-letter IATA airport code for arrival.
     * @var string
     */
    public $arrival_airport;

    /**
     * The stop over code (e.g., 'x' for connection).
     * @var string
     */
    public $stop_over_code;

    /**
     * The fare basis code.
     * @var string
     */
    public $fare_basis_code;
}
