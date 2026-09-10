<?php

namespace Checkout\Payments;

use DateTime;
use Checkout\Common\DateOnly;

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
     * Format: yyyy-MM-dd
     * @var DateTime
     */
    #[DateOnly]
    public $date_of_birth;
}
