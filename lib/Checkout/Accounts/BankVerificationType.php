<?php

namespace Checkout\Accounts;

/**
 * The document type accepted as bank verification when onboarding a sub-entity.
 *
 * Separate from Common\DocumentType, which lists identity documents (passport, national
 * identity card, driving license). The API models this one as its own enum whose only
 * accepted value is bank_statement, so offering it alongside the identity documents would
 * suggest it is valid where the API rejects it, and vice versa.
 *
 * Applies to the EEA, GB and US variants, company and sole trader alike, on schema 2.0
 * and 3.0. Also separate from InstrumentDocumentType, which happens to carry the same
 * bank_statement value but belongs to the document on a payment instrument: the spec keeps
 * the two enums apart and so do we.
 */
class BankVerificationType
{
    public static $bank_statement = "bank_statement";
}
