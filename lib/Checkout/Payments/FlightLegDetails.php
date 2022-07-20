<?php

namespace Checkout\Payments;

class FlightLegDetails
{
    /**
     * @var int
     */
    public $flight_number;

    /**
     * @var string
     */
    public $carrier_code;

    /**
     * @var string
     */
    public $service_class;

    /**
     * @var string
     */
    public $departure_date;

    /**
     * @var string
     */
    public $departure_time;

    /**
     * @var string
     */
    public $departure_airport;

    /**
     * @var string
     */
    public $arrival_airport;

    /**
     * @var string
     */
    public $stopover_code;

    /**
     * @var string
     */
    public $fare_basis_code;
}
