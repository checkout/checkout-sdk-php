<?php

namespace Checkout\Accounts;

/**
 * The terms of service the sub-entity agreed to (Accounts API v3.0, SaaS onboarding).
 */
class AgreedTerms
{
    /**
     * The date and time the terms were agreed.
     * [Required]
     * Format: date-time (RFC 3339)
     *
     * @var string
     */
    public $date;

    /**
     * The IP address from which the terms were agreed.
     * [Required]
     *
     * @var string
     */
    public $ip_address;

    /**
     * The name of the person who agreed to the terms.
     * [Required]
     *
     * @var string
     */
    public $name;

    /**
     * The email address of the person who agreed to the terms.
     * [Required]
     * Format: email
     *
     * @var string
     */
    public $email;

    /**
     * The version of the terms that were agreed.
     * [Required]
     *
     * @var string
     */
    public $version;
}
