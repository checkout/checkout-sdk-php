<?php

namespace Checkout\Accounts;

/**
 * The document type accepted as financial verification when onboarding a sub-entity (EEA Company,
 * Accounts API v2.0).
 *
 * Note the singular financial_statement. It is a different enum from FinancialStatementsType,
 * whose value is the plural financial_statements and which belongs to a different document.
 */
class FinancialVerificationType
{
    public static $financial_statement = "financial_statement";
}
