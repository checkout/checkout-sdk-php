<?php

namespace Checkout\Accounts;

/**
 * ACH payment processing details (Accounts API v3.0).
 */
class ProcessingDetailsAch
{
    /**
     * The estimated annual ACH processing volume, in the minor currency unit.
     * [Required]
     * Minimum: 0
     *
     * @var int
     */
    public $annual_ach_volume;

    /**
     * The estimated average ACH transaction size, in the minor currency unit.
     * [Required]
     * Minimum: 0
     *
     * @var int
     */
    public $average_ach_transaction_size;

    /**
     * The estimated monthly credit volume, in the minor currency unit.
     * [Required]
     * Minimum: 0
     *
     * @var int
     */
    public $estimated_monthly_credit_volume;

    /**
     * The estimated average credit amount, in the minor currency unit.
     * [Required]
     * Minimum: 0
     *
     * @var int
     */
    public $average_credit_amount;
}
