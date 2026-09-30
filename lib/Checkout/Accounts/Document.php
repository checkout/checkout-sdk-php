<?php

namespace Checkout\Accounts;

/**
 * An identity document, as used for identity_verification on the representative (Accounts API
 * v3.0) or on the top-level documents of the sole trader variants (Accounts API v2.0).
 */
class Document
{
    /**
     * The type of document used for identity verification.
     * [Required]
     * Enum: "passport", "national_identity_card", "driving_license", "citizen_card",
     * "residence_permit", "electoral_id"
     *
     * @var string value of \Checkout\Common\DocumentType
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

    /**
     * The ID of the back side of the document as represented within Checkout.com systems.
     * [Optional]
     * ^file_[a-z2-7]{26}$
     * Length: 31 characters
     *
     * @var string
     */
    public $back;
}
