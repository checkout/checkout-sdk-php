<?php

namespace Checkout\Payments;

class AirlineData
{
    /**
     * @var Ticket
     */
    public $ticket;

    /**
     * @var Passenger
     */
    public $passenger;

    /**
     * @var array of FlightLegDetails
     */
    public $flight_leg_details;
}
