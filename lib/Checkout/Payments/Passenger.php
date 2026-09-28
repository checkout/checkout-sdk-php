<?php

namespace Checkout\Payments;

use DateTime;
use Checkout\Common\DateOnly;

/**
 * Contains information about a passenger on the flight.
 */
class Passenger
{
    /**
     * The passenger's first name.
     * @var string
     */
    public $first_name;

    /**
     * The passenger's last name.
     * @var string
     */
    public $last_name;

    /**
     * The passenger's date of birth.
     * Format: yyyy-MM-dd
     * @var DateTime
     */
    #[DateOnly]
    public $date_of_birth;

    /**
     * The passenger's address information.
     * @var PassengerAddress
     */
    public $address;
}
