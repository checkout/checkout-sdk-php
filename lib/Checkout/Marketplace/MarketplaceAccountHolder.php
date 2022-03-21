<?php

namespace Checkout\Marketplace;

use Checkout\Common\Four\AccountHolder;

class MarketplaceAccountHolder extends AccountHolder
{
    public $type;

    public $company_name;

    public $tax_id;

    // DateOfBirth
    public $date_of_birth;

    public $country_of_birth;

    public $residential_status;

    // Identification
    public $identification;

    public $email;
}
