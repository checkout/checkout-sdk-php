<?php

namespace Checkout\Accounts;

/**
 * Audited or management-prepared financial statements (when applicable). US ISV Seller variants.
 *
 * Not the same document as FinancialVerification, whose type is the singular
 * financial_statement.
 */
class FinancialStatements
{
    /**
     * The type of document.
     * [Required]
     * Enum: "financial_statements"
     *
     * @var string value of FinancialStatementsType
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
