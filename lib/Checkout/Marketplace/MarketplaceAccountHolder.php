<?php

namespace Checkout\Marketplace;

use Checkout\Common\Country;
use Checkout\Common\Four\AccountHolder;

class MarketplaceAccountHolder extends AccountHolder
{
    /**
     * @var MarketplaceAccountHolderType
     */
    public $type;

    /**
     * @var string
     */
    public $company_name;

    /**
     * @var string
     */
    public $tax_id;

    /**
     * @var DateOfBirth
     */
    public $date_of_birth;

    /**
     * @var Country
     */
    public $country_of_birth;

    /**
     * @var string
     */
    public $residential_status;

    /**
     * @var Identification
     */
    public $identification;

    /**
     * @var string
     */
    public $email;
}
