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
     * Identity verification document.
     * [Optional]
     *
     * @var Document
     */
    public $identity_verification;

    /**
     * Company verification document.
     * [Optional]
     *
     * @var CompanyVerification
     */
    public $company_verification;

    /**
     * Tax verification document.
     * [Optional]
     *
     * @var TaxVerification
     */
    public $tax_verification;

    /**
     * Memorandum or articles of association document.
     * [Optional] (required for the company full onboarding variants)
     *
     * @var ArticlesOfAssociation
     */
    public $articles_of_association;

    /**
     * Shareholder structure document.
     * [Optional] (required for the company full onboarding variants)
     *
     * @var ShareholderStructure
     */
    public $shareholder_structure;

    /**
     * Bank verification document: a document showing transactions from the last 3 months.
     * [Optional] (required for the EEA, GB and US company and sole trader full onboarding
     * variants)
     *
     * @var BankVerification
     */
    public $bank_verification;

    /**
     * Financial statements document.
     * [Optional]
     *
     * @var FinancialStatements
     */
    public $financial_statements;

    /**
     * Financial statement document. Becomes mandatory depending on the answer provided for
     * annual_processing_volume.
     * [Optional] (EEA Company Full (2.0))
     *
     * @var FinancialVerification
     */
    public $financial_verification;

    /**
     * Proof of the company's principal place of business.
     * [Optional] (company variants, Accounts API v3.0)
     *
     * @var ProofOfPrincipalAddress
     */
    public $proof_of_principal_address;

    /**
     * A regulatory licence document required for the company to operate (when applicable).
     * [Optional] (company variants, Accounts API v3.0)
     *
     * @var ProofOfLegality
     */
    public $proof_of_legality;

    /**
     * Additional space for documents to be provided when requested.
     * [Optional] (Accounts API v3.0)
     *
     * @var AdditionalDocument
     */
    public $additional_document1;

    /**
     * Additional space for documents to be provided when requested.
     * [Optional] (Accounts API v3.0)
     *
     * @var AdditionalDocument
     */
    public $additional_document2;

    /**
     * Additional space for documents to be provided when requested.
     * [Optional] (Accounts API v3.0)
     *
     * @var AdditionalDocument
     */
    public $additional_document3;
}
