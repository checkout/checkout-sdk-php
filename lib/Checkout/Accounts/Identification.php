<?php

namespace Checkout\Accounts;

/**
 * The identification of a representative or individual on the Accounts API v2.0 US variants.
 */
class Identification
{
    /**
     * Social Security Number (SSN), or Individual Taxpayer Identification Number (ITIN) for
     * non-US citizens.
     * [Required]
     * ^\d{9}$
     * Length: 9 characters
     *
     * @var string
     */
    public $national_id_number;

    /**
     * Not defined by the Accounts API: the identification object carries national_id_number only.
     * Retained so existing code keeps compiling; the API does not read it.
     *
     * @var Document
     * @deprecated Not part of any Accounts API schema. On v3.0, send identity documents through
     *             RepresentativeDocuments::$identity_verification.
     */
    public $document;
}
