<?php

namespace Checkout\Payments\Request;

/**
 * The billing descriptor of a bank payout.
 * Beneficiary banks typically construct the billing descriptor using the billing descriptor reference
 * and sender information. For direct sellers, the sender is typically your entity's legal company name.
 */
class PayoutBillingDescriptor
{
    /**
     * The reference shown on the beneficiary's bank statement.
     * The length varies per beneficiary bank. In general, we recommend fewer than 11 characters.
     * References are not supported in the US.
     * [Required]
     * min 11 characters
     * max 15 characters
     * @var string
     */
    public $reference;
}
