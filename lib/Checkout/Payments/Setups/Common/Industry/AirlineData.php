<?php

namespace Checkout\Payments\Setups\Common\Industry;

use Checkout\Payments\Ticket;

class AirlineData
{
    /**
     * @var Ticket
     */
    public $ticket;

    /**
     * @var array of Passenger
     */
    public $passengers;

    /**
     * @var array of FlightLegDetails
     */
    public $flight_leg_details;
}
