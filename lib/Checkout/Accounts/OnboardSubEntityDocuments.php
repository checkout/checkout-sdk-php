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
     * Articles of association document.
     * [Optional] (required for the company full onboarding variants)
     *
     * @var Document
     */
    public $articles_of_association;

    /**
     * Shareholder structure document.
     * [Optional] (required for the company full onboarding variants)
     *
     * @var Document
     */
    public $shareholder_structure;

    /**
     * Bank verification document.
     * [Optional] (required for the EEA company and sole trader full onboarding variants)
     *
     * @var Document
     */
    public $bank_verification;

    /**
     * Financial statements document.
     * [Optional]
     *
     * @var Document
     */
    public $financial_statements;

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
