<?php

namespace Checkout\Accounts;

/**
 * Proof of residential address of the representative.
 *
 * Belongs on company.representatives[].documents (see RepresentativeDocuments), never on the
 * top-level request documents. Required for the EEA Sole Trader - Full (3.0) variant.
 */
class ProofOfResidentialAddress
{
    /**
     * The type of document being used as address verification.
     * [Required]
     * Enum: "proof_of_address"
     *
     * @var string value of ProofOfResidentialAddressType
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
