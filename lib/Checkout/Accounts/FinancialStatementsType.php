<?php

namespace Checkout\Accounts;

/**
 * The document type accepted as financial statements when onboarding a sub-entity (US ISV Seller
 * variants).
 *
 * Note the plural financial_statements. It is a different enum from FinancialVerificationType,
 * whose value is the singular financial_statement and which belongs to a different document.
 */
class FinancialStatementsType
{
    public static $financial_statements = "financial_statements";
}
