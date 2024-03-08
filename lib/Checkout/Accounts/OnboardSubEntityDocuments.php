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
}
