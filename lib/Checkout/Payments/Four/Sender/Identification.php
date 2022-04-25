<?php

namespace Checkout\Payments\Four\Sender;

use Checkout\Common\Country;

class Identification
{
    /**
     * @var IdentificationType
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
