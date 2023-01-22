<?php

namespace Checkout\Payments;

use Checkout\Common\Address;

class PaymentRecipient
{
    /**
     * @var string
     */
    public $dob;

    /**
     * @var string
     */
    public $account_number;

    /**
     * @var Address
     */
    public $address;

    /**
     * @var string
     */
    public $zip;

    /**
     * @var string
     */
    public $first_name;

    /**
     * @var string
     */
    public $last_name;

    /**
     * @var string
     */
    public $country;
}
