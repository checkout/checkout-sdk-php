<?php

namespace Checkout\Identities\Entities;

class IdvAddress
{
    /**
     * The first line of the address.
     * [Optional]
     * max 250 characters
     * Example: 123 Main Street
     * @var string|null
     */
    public $address_line1;

    /**
     * The second line of the address.
     * [Optional]
     * max 250 characters
     * Example: Apt 4B
     * @var string|null
     */
    public $address_line2;

    /**
     * The city or town.
     * [Optional]
     * max 50 characters
     * Example: London
     * @var string|null
     */
    public $city;

    /**
     * The state, county, or province.
     * [Optional]
     * max 50 characters
     * Example: Greater London
     * @var string|null
     */
    public $state;

    /**
     * The postal or ZIP code.
     * [Optional]
     * max 50 characters
     * Example: SW1A 1AA
     * @var string|null
     */
    public $zip;

    /**
     * The two-letter ISO country code of the address.
     * [Optional]
     * Standard: ISO 3166-1 alpha-2 country code
     * max 2 characters
     * Example: GB
     * @var string|null values of Checkout\Common\CountryCode
     */
    public $country;
}
