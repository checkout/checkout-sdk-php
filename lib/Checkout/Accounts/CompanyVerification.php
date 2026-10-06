<?php

namespace Checkout\Accounts;

/**
 * The document to use to confirm the company's identity (certified by a power of attorney within
 * the last 3 months).
 */
class CompanyVerification
{
    /**
     * The type of document used for company verification.
     * [Required]
     * Enum: "incorporation_document", "articles_of_association"
     * (articles_of_association is accepted on the US Company (2.0) variants only)
     *
     * @var string value of CompanyVerificationType
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
