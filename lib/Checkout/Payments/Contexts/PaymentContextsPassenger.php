<?php

namespace Checkout\Payments\Contexts;

use Checkout\Common\Address;
use Checkout\Common\DateOnly;

/**
 * Contains information about a passenger on the flight.
 */
class PaymentContextsPassenger
{
    /**
     * @var string
     */
    public $first_name;

    /**
     * @var string
     */
    public $last_name;

    /**
     * Format: yyyy-MM-dd
     * @var DateTime
     */
    #[DateOnly]
    public $date_of_birth;

    /**
     * @var Address
     */
    public $address;
}
