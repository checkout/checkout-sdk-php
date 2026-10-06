<?php

namespace Checkout\Accounts;

/**
 * Financial statement document. Becomes mandatory depending on the answer provided for
 * annual_processing_volume; the sub-entity's status changes to requirements_due when it is
 * needed.
 */
class FinancialVerification
{
    /**
     * The type of the file.
     * [Required]
     * Enum: "financial_statement"
     *
     * @var string value of FinancialVerificationType
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
