<?php

namespace Checkout\Common;

/**
 * The account holder's billing address on a SEPA payment source.
 *
 * Mirrors the `billing_address` object of `PaymentRequestSEPAV4Source.account_holder`,
 * where every property is required. Deliberately not {@see Address}, which also
 * declares `state` - a property this position does not accept.
 */
class SepaSourceBillingAddress
{
    /**
     * The account holder's street name.
     * [Required]
     *
     * @var string
     */
    public $address_line1;

    /**
     * The account holder's street number.
     * [Required] max 10 characters
     *
     * @var string
     */
    public $address_line2;

    /**
     * The account holder's city.
     * [Required] max 35 characters
     *
     * @var string
     */
    public $city;

    /**
     * The account holder's zip code.
     * [Required] max 16 characters
     *
     * @var string
     */
    public $zip;

    /**
     * The account holder's country, as an ISO 3166-1 alpha-2 code.
     * [Required] max 2 characters
     *
     * @var string value of Country
     */
    public $country;
}
