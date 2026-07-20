<?php

namespace Checkout\Accounts;

/**
 * The expected payment processing details of a sub-entity (Accounts API v3.0).
 */
class ProcessingDetails
{
    /**
     * The estimated annual processing volume, in the minor currency unit.
     * [Required]
     * Minimum: 0
     *
     * @var int
     */
    public $annual_processing_volume;

    /**
     * The estimated average transaction value, in the minor currency unit.
     * [Required]
     * Minimum: 0
     *
     * @var int
     */
    public $average_transaction_value;

    /**
     * The estimated average order fulfillment time, in days.
     * [Required]
     * Minimum: 0
     *
     * @var int
     */
    public $average_order_fulfillment_time;

    /**
     * The currency used for the processing details provided.
     * [Required]
     * Format: ISO 4217 (three-letter currency code)
     *
     * @var string value of Currency
     */
    public $currency;

    /**
     * The countries the sub-entity intends to sell to.
     * [Required]
     * Format: array of ISO 3166-1 alpha-2 country codes
     *
     * @var array of Country
     */
    public $target_countries;

    /**
     * Payment method-specific processing details.
     * [Required]
     *
     * @var ProcessingDetailsPayments
     */
    public $payments;
}
