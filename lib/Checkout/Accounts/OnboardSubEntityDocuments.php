<?php

namespace Checkout\Accounts;

/**
 * The documents supplied to onboard a sub-entity. Which documents are required depends on the
 * sub-entity's region and business type (see the Accounts API onboarding schema variants).
 *
 * This is the top-level request documents object. The API ignores keys it does not recognise here
 * rather than rejecting them, so a misplaced document is dropped silently. The representative's
 * own documents (proof_of_residential_address, proof_of_registration,
 * certified_authorised_signatory) do not go here: set them on Representative::$documents, using
 * RepresentativeDocuments.
 */
class OnboardSubEntityDocuments
{
    /**
     * The document to use to confirm the individual's identity.
     * [Required] for the six sole trader variants of Accounts API v2.0 (EEA, GB and US, Full and
     * Lite), the only variants that take it at this level. On v3.0 it belongs on
     * Representative::$documents instead.
     *
     * @var Document
     */
    public $identity_verification;

    /**
     * The document to use to confirm the company's identity (certified by a power of attorney
     * within the last 3 months).
     * [Required] for EEA Company Full (2.0 and 3.0) and GB Company Full (2.0); [Optional] for the
     * other company variants and the US ISV Seller variants.
     *
     * @var CompanyVerification
     */
    public $company_verification;

    /**
     * IRS-issued Employer Identification Number document used to verify the entity's tax
     * identification.
     * [Optional] (US Company variants and the US ISV Seller variants only)
     *
     * @var TaxVerification
     */
    public $tax_verification;

    /**
     * Memorandum or Articles of Association document.
     * [Required] for EEA and GB Company Full (3.0); [Optional] for US Company Full (3.0) and the
     * US ISV Seller variants.
     *
     * @var ArticlesOfAssociation
     */
    public $articles_of_association;

    /**
     * Shareholder structure chart (including % of shares) certified by a competent authority
     * individual and dated within the last 3 months.
     * [Required] for EEA and GB Company Full (3.0); [Optional] for US Company Full (3.0) and US ISV
     * Seller Company (3.0).
     *
     * @var ShareholderStructure
     */
    public $shareholder_structure;

    /**
     * A document showing transactions from the last 3 months.
     * [Required] for EEA Company Full (3.0) and the EEA, GB and US Sole Trader Full (3.0) variants;
     * [Optional] for GB and US Company Full (3.0) and EEA Company Full and Lite (2.0).
     *
     * @var BankVerification
     */
    public $bank_verification;

    /**
     * Audited or management-prepared financial statements (when applicable).
     * [Optional] (US ISV Seller variants only)
     *
     * @var FinancialStatements
     */
    public $financial_statements;

    /**
     * Financial statement document. Becomes mandatory depending on the answer provided for
     * annual_processing_volume; the sub-entity's status changes to requirements_due when it is
     * needed.
     * [Optional] (EEA Company Full and Lite (2.0) only)
     *
     * @var FinancialVerification
     */
    public $financial_verification;

    /**
     * Proof of the company's principal place of business.
     * [Optional] (EEA, GB and US Company Full (3.0) and the US ISV Seller variants)
     *
     * @var ProofOfPrincipalAddress
     */
    public $proof_of_principal_address;

    /**
     * A regulatory licence document required for the company to operate (when applicable).
     * [Optional] (EEA, GB and US Company Full (3.0) and the US ISV Seller variants)
     *
     * @var ProofOfLegality
     */
    public $proof_of_legality;

    /**
     * Additional space for documents to be provided when requested.
     * [Optional] (EEA, GB and US Company and Sole Trader Full (3.0); not the US ISV Seller variants)
     *
     * @var AdditionalDocument
     */
    public $additional_document1;

    /**
     * Additional space for documents to be provided when requested.
     * [Optional] (EEA, GB and US Company and Sole Trader Full (3.0); not the US ISV Seller variants)
     *
     * @var AdditionalDocument
     */
    public $additional_document2;

    /**
     * Additional space for documents to be provided when requested.
     * [Optional] (EEA, GB and US Company and Sole Trader Full (3.0); not the US ISV Seller variants)
     *
     * @var AdditionalDocument
     */
    public $additional_document3;
}
