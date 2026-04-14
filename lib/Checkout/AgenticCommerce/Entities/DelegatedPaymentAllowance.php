<?php

namespace Checkout\AgenticCommerce\Entities;

/**
 * The spending constraints that define what the delegated payment token is authorized for.
 */
class DelegatedPaymentAllowance
{
    /**
     * The reason for the allowance.
     * [Required]
     * Enum: "one_time"
     *
     * @var string value of DelegatedPaymentAllowanceReason
     */
    public $reason;

    /**
     * The maximum amount that can be charged using the delegated payment token, in the minor currency unit.
     * [Required]
     *
     * @var int
     */
    public $max_amount;

    /**
     * The three-letter ISO 4217 currency code.
     * [Required]
     * Length: 3 characters
     *
     * @var string
     */
    public $currency;

    /**
     * The unique identifier of the merchant that will process the payment.
     * [Required]
     * Length: <= 256 characters
     *
     * @var string
     */
    public $merchant_id;

    /**
     * The identifier of the checkout session associated with this delegated payment.
     * [Required]
     *
     * @var string
     */
    public $checkout_session_id;

    /**
     * The expiry time of the delegated payment token, in RFC 3339 format. Must be a time in the future.
     * [Required]
     * Format: date-time (RFC 3339)
     *
     * @var string
     */
    public $expires_at;
}
