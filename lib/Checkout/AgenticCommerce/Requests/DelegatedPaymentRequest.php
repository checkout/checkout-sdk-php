<?php

namespace Checkout\AgenticCommerce\Requests;

use Checkout\AgenticCommerce\Entities\DelegatedPaymentMethodCard;
use Checkout\AgenticCommerce\Entities\DelegatedPaymentAllowance;
use Checkout\AgenticCommerce\Entities\DelegatedPaymentBillingAddress;
use Checkout\AgenticCommerce\Entities\DelegatedPaymentRiskSignal;

/**
 * [Beta]
 * The request body for creating a delegated payment token (POST /agentic_commerce/delegate_payment).
 */
class DelegatedPaymentRequest
{
    /**
     * The card payment method details.
     * [Required]
     *
     * @var DelegatedPaymentMethodCard
     */
    public $payment_method;

    /**
     * The spending constraints for the delegated payment token.
     * [Required]
     *
     * @var DelegatedPaymentAllowance
     */
    public $allowance;

    /**
     * The customer billing address.
     * [Optional]
     *
     * @var DelegatedPaymentBillingAddress|null
     */
    public $billing_address;

    /**
     * An array of risk assessment signals provided by the platform.
     * [Required]
     *
     * @var DelegatedPaymentRiskSignal[]
     */
    public $risk_signals;

    /**
     * A set of key-value pairs to attach to the delegated payment request (string values only).
     * [Required]
     *
     * @var array<string, string>
     */
    public $metadata;
}
