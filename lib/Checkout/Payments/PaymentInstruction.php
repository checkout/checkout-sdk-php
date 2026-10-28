<?php

namespace Checkout\Payments;

/**
 * The instruction of a regular payment (PaymentRequest) and of a payment session submit or complete request.
 * Regular payments, hosted payments, payment links and payment sessions use purpose only. This class does
 * not declare purpose; scheme and remittance are the bank payout instruction keys, also available on
 * Checkout\Payments\Request\PaymentInstruction.
 */
class PaymentInstruction
{
    /**
     * An option to restrict the payment scheme type; payouts will fail if unavailable.
     * [Optional]
     * Enum: "local" "instant"
     * @var string|null $scheme
     */
    public $scheme;

    /**
     * Remittance details for the payment, used for reconciliation.
     * [Optional]
     * @var InstructionRemittance|null $remittance
     */
    public $remittance;
}
