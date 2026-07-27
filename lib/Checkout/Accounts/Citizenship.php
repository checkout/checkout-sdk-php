<?php

namespace Checkout\Accounts;

/**
 * A citizenship or legal status held by a company representative, as required by the
 * Accounts API v3.0 individual schema.
 */
class Citizenship
{
    /**
     * The type of citizenship or legal status (for example, "citizenship" or "residency").
     * [Optional]
     *
     * @var string
     */
    public $type;

    /**
     * The two-letter ISO 3166-1 alpha-2 country code.
     * [Required]
     *
     * @var string value of Country
     */
    public $country;
}
