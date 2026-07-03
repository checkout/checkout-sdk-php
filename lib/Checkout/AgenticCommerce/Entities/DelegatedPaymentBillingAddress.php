<?php

namespace Checkout\AgenticCommerce\Entities;

/**
 * The customer billing address associated with the delegated payment.
 */
class DelegatedPaymentBillingAddress
{
    /**
     * The full name of the customer.
     * [Required]
     * Length: max 256 characters
     *
     * @var string
     */
    public $name;

    /**
     * The first line of the street address.
     * [Required]
     * Length: max 60 characters
     *
     * @var string
     */
    public $line_one;

    /**
     * The second line of the street address.
     * [Optional]
     * Length: max 60 characters
     *
     * @var string|null
     */
    public $line_two;

    /**
     * The city of the billing address.
     * [Required]
     * Length: max 60 characters
     *
     * @var string
     */
    public $city;

    /**
     * The state, county, province, or region of the billing address.
     * [Optional]
     *
     * @var string|null
     */
    public $state;

    /**
     * The postal code or ZIP code of the billing address.
     * [Required]
     * Length: max 20 characters
     *
     * @var string
     */
    public $postal_code;

    /**
     * The two-letter ISO 3166-1 alpha-2 country code.
     * [Required]
     * Length: 2 characters
     *
     * @var string
     */
    public $country;
}
