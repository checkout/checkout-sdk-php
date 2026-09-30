<?php

namespace Checkout\Accounts;

/**
 * Proof of the company's principal place of business.
 */
class ProofOfPrincipalAddress
{
    /**
     * The type of document being used as address verification.
     * [Required]
     * Enum: "proof_of_address"
     *
     * @var string value of ProofOfPrincipalAddressType
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
