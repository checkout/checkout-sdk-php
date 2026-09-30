<?php

namespace Checkout\Accounts;

/**
 * Proof of the sole trader's registration, for example an extract from a trade register.
 *
 * Belongs on company.representatives[].documents (see RepresentativeDocuments), never on the
 * top-level request documents. Required for the EEA Sole Trader - Full (3.0) variant.
 */
class ProofOfRegistration
{
    /**
     * The type of document being used as proof of registration.
     * [Required]
     * Enum: "extract_from_trade_register", "other"
     *
     * @var string value of ProofOfRegistrationType
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
