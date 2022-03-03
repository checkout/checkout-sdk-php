<?php

namespace Checkout\Marketplace;

use Checkout\Common\Address;

class Company
{
    public string $business_registration_number;

    public string $legal_name;

    public string $trading_name;

    public Address $principal_address;

    public Address $registered_address;

    public array $representatives;
}
