<?php

namespace Checkout\Accounts;

class OnboardSubEntityDocuments
{
    /**
     * @var Document
     */
    public $identity_verification;

    /**
     * @var CompanyVerification
     */
    public $company_verification;

    /**
     * @var TaxVerification
     */
    public $tax_verification;

    /**
     * @var Document articles of association (v3.0)
     */
    public $articles_of_association;

    /**
     * @var Document shareholder structure document (v3.0)
     */
    public $shareholder_structure;

    /**
     * @var Document bank verification document (v3.0)
     */
    public $bank_verification;

    /**
     * @var Document proof of principal address (v3.0)
     */
    public $proof_of_principal_address;

    /**
     * @var Document proof of legality (v3.0)
     */
    public $proof_of_legality;

    /**
     * @var Document additional supporting document (v3.0)
     */
    public $additional_document1;

    /**
     * @var Document additional supporting document (v3.0)
     */
    public $additional_document2;

    /**
     * @var Document additional supporting document (v3.0)
     */
    public $additional_document3;
}
