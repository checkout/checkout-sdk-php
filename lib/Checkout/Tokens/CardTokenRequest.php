<?php

namespace Checkout\Tokens;

use Checkout\Common\Address;
use Checkout\Common\Phone;

class CardTokenRequest
{
    public string $type = "card";

    public string $number;

    public int $expiry_month;

    public int $expiry_year;

    public string $name;

    public string $cvv;

    public Address $billing_address;

    public Phone $phone;

}
