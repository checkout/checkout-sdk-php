<?php

namespace Checkout\Instruments\Update;

/**
 * The billing address of the account holder of a SEPA instrument being updated.
 */
class UpdateSepaBillingAddress
{
    /**
     * The first line of the address.
     * [Required]
     * max 200 characters
     * @var string
     */
    public $address_line1;

    /**
     * The street number. If no number, pass "w/n".
     * [Required]
     * max 10 characters
     * @var string
     */
    public $address_line2;

    /**
     * The address city.
     * [Required]
     * max 50 characters
     * @var string
     */
    public $city;

    /**
     * The address zip/postal code.
     * [Required]
     * max 50 characters
     * @var string
     */
    public $zip;

    /**
     * The two-letter ISO country code of the address.
     * [Required]
     * min 2 characters, max 2 characters
     * @var string values of Checkout\Common\Country
     */
    public $country;
}
