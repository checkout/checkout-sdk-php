<?php

namespace Checkout\Payments;

use DateTime;

class AccommodationGuest
{
    /**
     * The guest's first name.
     * @var string
     */
    public $first_name;

    /**
     * The guest's last name.
     * @var string
     */
    public $last_name;

    /**
     * The guest's date of birth.
     * @var DateTime
     */
    public $date_of_birth;
}
