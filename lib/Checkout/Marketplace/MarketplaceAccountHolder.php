<?php

namespace Checkout\Marketplace;

use Checkout\Common\Four\AccountHolder;

class MarketplaceAccountHolder extends AccountHolder
{
    public string $type;

    public string $company_name;

    public string $tax_id;

    public DateOfBirth $date_of_birth;

    public string $country_of_birth;

    public string $residential_status;

    public Identification $identification;

    public string $email;
}
