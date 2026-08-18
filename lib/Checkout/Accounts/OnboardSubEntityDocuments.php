<?php

namespace Checkout\Accounts;

/**
 * The documents supplied to onboard a sub-entity. Which documents are required depends on the
 * sub-entity's region and business type (see the Accounts API onboarding schema variants).
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
     * Financial verification document.
     * [Optional]
     *
     * @var Document
     */
    public $financial_verification;

    /**
     * Proof of principal address document.
     * [Optional]
     *
     * @var Document
     */
    public $proof_of_principal_address;

    /**
     * Proof of legality document.
     * [Optional]
     *
     * @var Document
     */
    public $proof_of_legality;

    /**
     * Additional supporting document.
     * [Optional]
     *
     * @var Document
     */
    public $additional_document1;

    /**
     * Additional supporting document.
     * [Optional]
     *
     * @var Document
     */
    public $additional_document2;

    /**
     * Additional supporting document.
     * [Optional]
     *
     * @var Document
     */
    public $additional_document3;
}
