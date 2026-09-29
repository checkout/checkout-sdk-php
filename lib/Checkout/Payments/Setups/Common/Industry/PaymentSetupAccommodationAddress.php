<?php

namespace Checkout\Payments\Setups\Common\Industry;

/**
 * The accommodation's address, as declared on PaymentSetupAccommodation.address.
 *
 * Deliberately separate from Checkout\Payments\AccommodationAddress, which declares only
 * address_line1 and zip because that is all the payments schema defines. This schema adds city,
 * state and country, so reusing the payments class made those three impossible to send.
 */
class PaymentSetupAccommodationAddress
{
    /**
     * The first line of the address.
     * [Optional]
     *
     * @var string
     */
    public $address_line1;

    /**
     * The address city.
     * [Optional]
     *
     * @var string
     */
    public $city;

    /**
     * The address state or county.
     * [Optional]
     *
     * @var string
     */
    public $state;

    /**
     * The address country, in ISO 3166-1 alpha-2 format.
     * [Optional]
     *
     * @var string
     */
    public $country;

    /**
     * The zip code or postal code.
     * [Optional]
     *
     * @var string
     */
    public $zip;
}
