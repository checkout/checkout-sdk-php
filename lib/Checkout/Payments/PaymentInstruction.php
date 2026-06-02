<?php

namespace Checkout\Payments;

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
