<?php

namespace Checkout\Common\Four;

use Checkout\Common\Address;
use Checkout\Common\Phone;

class AccountHolder
{
    public string $first_name;

    public string $last_name;

    public Address $billing_address;

    public Phone $phone;

}
