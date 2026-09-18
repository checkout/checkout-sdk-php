<?php

namespace Checkout\Identities\Entities;

class ClientInformation
{
    /**
     * The applicant's residence country.
     * [Optional]
     * Standard: ISO 3166-1 alpha-2 country code
     * ^[A-Z]{2}
     * Example: FR
     * @var string|null values of Checkout\Common\CountryCode
     */
    public $pre_selected_residence_country;

    /**
     * The language you want to use for the user interface.
     * [Optional]
     * Format: IETF BCP 47 language tag
     * Example: en-US
     * @var string|null
     */
    public $pre_selected_language;
}
