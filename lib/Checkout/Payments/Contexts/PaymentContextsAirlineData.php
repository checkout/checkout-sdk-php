<?php

namespace Checkout\Payments\Contexts;

class PaymentContextsAirlineData
{
    /**
     * @var array of Checkout\Payments\Contexts\PaymentContextsTicket
     */
    public $tickets;

    /**
     * @var array of Checkout\Payments\Contexts\PaymentContextsPassenger
     */
    public $passengers;

    /**
     * @var array of Checkout\Payments\Contexts\PaymentContextsFlightLegDetails
     */
    public $flight_leg_details;
}
