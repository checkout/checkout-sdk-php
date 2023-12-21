<?php

namespace Checkout\Payments;

use Checkout\Common\Address;
use Checkout\Common\Phone;

class ShippingDetails
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
     * @var string
     */
    public $email;

    /**
     * @var Address
     */
    public $address;

    /**
     * @var Phone
     */
    public $phone;

    /**
     * @var string
     */
    public $from_address_zip;

    /**
     * @var string value of DeliveryTimeframe
     */
    public $timeframe;

    /**
     * @var string value of PaymentContextsShippingMethod
     */
    public $method;

    /**
     * @var int
     */
    public $delay;
}
