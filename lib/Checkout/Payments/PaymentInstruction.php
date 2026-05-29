<?php

namespace Checkout\Payments;

class PaymentInstruction
{
    /**
     * The purpose of payment for account funding transactions (AFT).
     * See PaymentPurpose class for available values.
     * @var string value of PaymentPurpose
     */
    public $purpose;

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
