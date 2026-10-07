<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\CashApp;

/**
 * The address in a Cash App customer profile (response only).
 *
 * Uses Cash App's own key names (address_line_1, administrative_district_level_1), which differ from the
 * Checkout.com Address (address_line1, city, state, zip). Do not replace it with the common Address.
 */
class CashAppAddress
{
    /**
     * The first line of the address.
     * [Optional]
     * readOnly
     * @var string
     */
    public $address_line_1;

    /**
     * The second line of the address.
     * [Optional]
     * readOnly
     * @var string
     */
    public $address_line_2;

    /**
     * The third line of the address.
     * [Optional]
     * readOnly
     * @var string
     */
    public $address_line_3;

    /**
     * The address locality, such as the city or town.
     * [Optional]
     * readOnly
     * @var string
     */
    public $locality;

    /**
     * The address sublocality, such as the district or neighborhood.
     * [Optional]
     * readOnly
     * @var string
     */
    public $sublocality;

    /**
     * The address's top-level administrative district, such as the state or province.
     * [Optional]
     * readOnly
     * @var string
     */
    public $administrative_district_level_1;

    /**
     * The postal or zip code.
     * [Optional]
     * readOnly
     * @var string
     */
    public $postal_code;

    /**
     * The address country, in ISO 3166-1 alpha-2 format.
     * [Optional]
     * readOnly
     * max 2 characters
     * @var string value of Country
     */
    public $country;
}
