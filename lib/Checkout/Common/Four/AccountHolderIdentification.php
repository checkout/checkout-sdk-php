<?php

namespace Checkout\Common\Four;

use Checkout\Common\Country;

class AccountHolderIdentification
{
    /**
     * @var AccountHolderIdentificationType
     */
    public $type;

    /**
     * @var string
     */
    public $number;

    /**
     * @var Country
     */
    public $issuing_country;
}
