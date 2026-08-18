<?php

namespace Checkout\Accounts;

/**
 * The document type accepted by InstrumentDocument, the legal document used to verify a bank
 * account when creating a payment instrument.
 *
 * Deliberately separate from Checkout\Common\DocumentType: that one lists identity documents
 * (passport, national identity card, driving license), and the API keeps the bank account
 * document type as its own enum, whose only accepted value is bank_statement. Offering
 * bank_statement alongside the identity documents would suggest it is valid where the API
 * rejects it, and the reverse.
 */
class InstrumentDocumentType
{
    public static $bank_statement = "bank_statement";
}
