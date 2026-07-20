<?php

namespace Checkout\Accounts;

/**
 * Request to onboard a sub-entity. Which fields are required depends on the sub-entity's region
 * and onboarding variant (see the Accounts API onboarding schema variants).
 */
class OnboardEntityRequest
{
    /**
     * A unique reference you can later use to identify the sub-entity.
     * [Required]
     * Length: 1 to 50 characters
     *
     * @var string
     */
    public $reference;

    /**
     * Contact details of the sub-entity.
     * [Required]
     *
     * @var ContactDetails
     */
    public $contact_details;

    /**
     * Information about the profile of the sub-entity.
     * [Required]
     *
     * @var Profile
     */
    public $profile;

    /**
     * Information about the company represented by the sub-entity.
     * [Required] for company and sole trader sub-entities (Accounts API v3.0)
     *
     * @var Company
     */
    public $company;

    /**
     * Information about the individual represented by the sub-entity.
     * [Optional]
     * Deprecated: not used by the Accounts API v3.0 schema; retained for v2.0 compatibility.
     *
     * @var Individual
     * @deprecated Use {@link Company} (with representatives) for the Accounts API v3.0 schema.
     */
    public $individual;

    /**
     * The verification documents for the sub-entity.
     * [Optional] (required for the full onboarding variants)
     *
     * @var OnboardSubEntityDocuments
     */
    public $documents;

    /**
     * Information about the sub-entity's expected processing.
     * [Required] (Accounts API v3.0)
     *
     * @var ProcessingDetails
     */
    public $processing_details;

    /**
     * Details of the person who agreed to the terms and conditions.
     * [Optional] (required for the SaaS onboarding variants, Accounts API v3.0)
     *
     * @var AgreedTerms
     */
    public $agreed_terms;

    /**
     * The identifier of a seller category configured for your platform.
     * [Optional] (required for the SaaS onboarding variants, Accounts API v3.0)
     *
     * @var string
     */
    public $seller_category;
}
