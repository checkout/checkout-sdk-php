<?php

namespace Checkout\Accounts;

/**
 * Payment method-specific processing details (Accounts API v3.0).
 */
class ProcessingDetailsPayments
{
    /**
     * ACH payment processing details.
     * [Required]
     *
     * @var ProcessingDetailsAch
     */
    public $ach;
}
