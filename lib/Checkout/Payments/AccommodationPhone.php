<?php

namespace Checkout\Payments;

/**
 * Phone contact information for an accommodation property.
 */
class AccommodationPhone
{
    /**
     * The phone country code.
     * [Optional]
     *
     * @var string
     */
    public $country_code;

    /**
     * The phone number.
     * [Optional]
     *
     * @var string
     */
    public $number;
}
