<?php

namespace Checkout\Payments;

use Checkout\Common\Country;

class PayPalAirlinePassenger
{
    /**
     * @var PayPalAirlinePassengerName
     */
    public $name;

    /**
     * @var string
     */
    public $date_of_birth;

    /**
     * @var Country
     */
    public $country_code;
}
