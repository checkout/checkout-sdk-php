<?php

namespace Checkout\Payments\Contexts;

use Checkout\Common\Address;

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
     * @var DateTime
     */
    public $date_of_birth;

    /**
     * @var Address
     */
    public $address;
}
