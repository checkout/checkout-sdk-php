<?php

namespace Checkout\Identities\Entities;

class PhoneNumber
{
    /**
     * The international phone country code. This is a dialling prefix, not an ISO country code.
     * [Required]
     * ^\+(\d+)$
     * Example: +33
     * @var string
     */
    public $country_code;

    /**
     * The applicant's mobile number, without the country code.
     * [Required]
     * ^\d{1,14}$
     * Example: 5555550102
     * @var string
     */
    public $number;
}
