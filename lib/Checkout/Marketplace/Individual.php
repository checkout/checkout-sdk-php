<?php

namespace Checkout\Marketplace;

use Checkout\Common\Address;

class Individual
{
    public string $first_name;

    public string $middle_name;

    public string $last_name;

    public string $legal_name;

    public string $trading_name;

    public string $national_tax_id;

    public Address $registered_address;

    public DateOfBirth $date_of_birth;

    public Identification $identification;
}
