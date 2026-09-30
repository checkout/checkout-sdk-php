<?php

namespace Checkout\Accounts;

/**
 * A regulatory licence document required for the company to operate (when applicable).
 */
class ProofOfLegality
{
    /**
     * The type of document used for proof of legality.
     * [Required]
     * Enum: "proof_of_legality"
     *
     * @var string value of ProofOfLegalityType
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
