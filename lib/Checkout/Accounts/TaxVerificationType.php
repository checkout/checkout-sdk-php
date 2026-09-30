<?php

namespace Checkout\Accounts;

/**
 * The document type accepted as tax verification when onboarding a sub-entity (US Company and
 * US ISV Seller variants): an IRS-issued Employer Identification Number letter.
 */
class TaxVerificationType
{
    public static $ein_letter = "ein_letter";
}
