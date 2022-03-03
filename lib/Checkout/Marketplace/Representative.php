<?php

namespace Checkout\Marketplace;

use Checkout\Common\Address;
use Checkout\Common\Phone;

class Representative
{
    public string $id;

    public string $first_name;

    public string $last_name;

    public Address $address;

    public Identification $identification;

    public Phone $phone;

    public DateOfBirth $date_of_birth;
}
