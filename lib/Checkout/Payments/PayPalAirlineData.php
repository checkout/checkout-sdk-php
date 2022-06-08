<?php

namespace Checkout\Payments;

class PayPalAirlineData
{
    /**
     * @var PayPalAirlineTicket
     */
    public $ticket;

    /**
     * @var PayPalAirlinePassenger
     */
    public $passenger;

    /**
     * @var array of PayPalAirlineFlightLegDetails
     */
    public $flight_leg_details;
}
