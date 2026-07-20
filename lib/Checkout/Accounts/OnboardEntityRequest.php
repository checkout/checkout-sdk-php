<?php

namespace Checkout\Accounts;

class OnboardEntityRequest
{
    /**
     * @var string
     */
    public $reference;

    /**
     * @var ContactDetails
     */
    public $contact_details;

    /**
     * @var Profile
     */
    public $profile;

    /**
     * @var Company
     */
    public $company;

    /**
     * @var Individual
     * @deprecated Not used by the Accounts API v3.0 schema; retained for v2.0 compatibility.
     */
    public $individual;

    /**
     * @var OnboardSubEntityDocuments
     */
    public $documents;

    /**
     * @var ProcessingDetails processing details of the sub-entity (v3.0)
     */
    public $processing_details;

    /**
     * @var AgreedTerms the terms the sub-entity agreed to (v3.0, SaaS onboarding)
     */
    public $agreed_terms;

    /**
     * @var string the identifier of a seller category configured for your platform (v3.0, SaaS onboarding)
     */
    public $seller_category;
}
