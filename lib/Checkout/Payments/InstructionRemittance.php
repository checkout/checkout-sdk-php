<?php

namespace Checkout\Payments;

class InstructionRemittance
{
    /**
     * A merchant-provided reference for the remittance payment.
     * This value is used for payment reconciliation and is reviewed by our banking partner.
     * [Optional]
     * max 80 characters
     * @var string|null $reference
     */
    public $reference;
}
