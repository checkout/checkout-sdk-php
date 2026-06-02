<?php

namespace Checkout\Tokens;

use Checkout\Common\Address;
use Checkout\Common\Phone;

class CardTokenRequest
{
    /**
     * @var string
     */
    public $type = "card";

    /**
     * @var string
     */
    public $number;

    /**
     * @var int
     */
    public $expiry_month;

    /**
     * @var int
     */
    public $expiry_year;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $cvv;

    /**
     * @var Address
     */
    public $billing_address;

    /**
     * @var Phone
     */
    public $phone;

    /**
     * The first 2 digits of the card PIN.
     * [Optional]
     * min 2 characters
     * max 2 characters
     * @var string|null $pin
     */
    public $pin;
}
