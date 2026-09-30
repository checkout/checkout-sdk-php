<?php

namespace Checkout\Accounts;

/**
 * A document showing transactions from the last 3 months.
 */
class BankVerification
{
    /**
     * The type of document being used as bank verification.
     * [Required]
     * Enum: "bank_statement"
     *
     * @var string value of BankVerificationType
     */
    public $type;

    /**
     * The ID of the front side of the document as represented within Checkout.com systems.
     * [Required]
     * ^file_[a-z2-7]{26}$
     * Length: 31 characters
     *
     * @var string
     */
    public $front;
}
