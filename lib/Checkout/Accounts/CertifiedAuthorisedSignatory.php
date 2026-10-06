<?php

namespace Checkout\Accounts;

/**
 * Certified authorised signatory document. Required when the legal representative or other role
 * owner is not registered on the certificate of incorporation.
 *
 * Belongs on company.representatives[].documents (see RepresentativeDocuments), never on the
 * top-level request documents. Used by the EEA, GB and US company variants.
 */
class CertifiedAuthorisedSignatory
{
    /**
     * The type of document.
     * [Required]
     * Enum: "power_of_attorney"
     *
     * @var string value of CertifiedAuthorisedSignatoryType
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
