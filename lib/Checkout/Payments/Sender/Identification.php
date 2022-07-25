<?php

namespace Checkout\Payments\Sender;

class Identification
{
    /**
     * @var string value of IdentificationType
     */
    public $type;

    /**
     * @var string
     */
    public $number;

    /**
     * @var string values of Country
     */
    public $issuing_country;
}
