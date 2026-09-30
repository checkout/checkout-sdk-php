<?php

namespace Checkout\Accounts;

/**
 * IRS-issued Employer Identification Number document used to verify the entity's tax
 * identification (US variants).
 */
class TaxVerification
{
    /**
     * The type of IRS-issued document used for tax verification.
     * [Required]
     * Enum: "ein_letter"
     *
     * @var string value of TaxVerificationType
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
