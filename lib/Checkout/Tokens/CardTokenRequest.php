<?php

namespace Checkout\Tokens;

class CardTokenRequest
{
    public $type = "card";

    public $number;

    public $expiry_month;

    public $expiry_year;

    public $name;

    public $cvv;

    // Address
    public $billing_address;

    // Phone
    public $phone;

}
