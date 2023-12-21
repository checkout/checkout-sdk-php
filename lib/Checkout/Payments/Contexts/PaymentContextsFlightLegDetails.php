<?php

namespace Checkout\Payments\Contexts;

class PaymentContextsFlightLegDetails
{
    /**
     * @var string
     */
    public $flight_number;

    /**
     * @var string
     */
    public $carrier_code;

    /**
     * @var string
     */
    public $class_of_travelling;

    /**
     * @var string
     */
    public $departure_airport;

    /**
     * @var DateTime
     */
    public $departure_date;

    /**
     * @var string
     */
    public $departure_time;

    /**
     * @var string
     */
    public $arrival_airport;

    /**
     * @var string
     */
    public $stop_over_code;

    /**
     * @var string
     */
    public $fare_basis_code;
}
