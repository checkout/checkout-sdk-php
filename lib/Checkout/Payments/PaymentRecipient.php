<?php

namespace Checkout\Payments;

use Checkout\Common\Country;

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
     * @var Country
     */
    public $country;
}
