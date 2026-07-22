<?php

namespace Checkout\Accounts;

use Checkout\Common\Address;
use Checkout\Common\Phone;

/**
 * A company representative ("person of interest") on the Accounts API. In the v3.0 schema the
 * personal details are nested under {@link $individual}; the flat person fields are retained for
 * v2.0 compatibility only.
 */
class Representative
{
    /**
     * The representative's id.
     * [Optional]
     * Length: 30 characters
     *
     * @var string
     */
    public $id;

    /**
     * The representative's personal details.
     * [Required] (Accounts API v3.0)
     *
     * @var RepresentativeIndividual
     */
    public $individual;

    /**
     * The roles the representative holds within the company.
     * [Required] (Accounts API v3.0)
     * Enum: "ubo", "authorised_signatory", "director", "control_person", "legal_representative"
     *
     * @var array values of EntityRoles
     */
    public $roles;

    /**
     * The position of the representative within the company.
     * [Optional]
     * Enum: "ceo", "cfo", "coo", "managing_member", "general_partner", "president",
     * "vice_president", "treasurer", "other_senior_management", "other_executive_officer",
     * "other_non_executive_non_senior"
     *
     * @var string value of CompanyPosition
     */
    public $company_position;

    /**
     * The percentage ownership of the UBO or controlling company.
     * [Optional]
     * Range: 0 to 100 (a minimum of 25 applies to the company full onboarding variants)
     *
     * @var int
     */
    public $ownership_percentage;

    /**
     * The representative's verification documents.
     * [Optional]
     *
     * @var OnboardSubEntityDocuments
     */
    public $documents;

    /**
     * The representative's first name.
     * [Optional]
     *
     * @var string
     * @deprecated Not used by the Accounts API v3.0 schema; use {@link $individual} instead.
     */
    public $first_name;

    /**
     * The representative's last name.
     * [Optional]
     *
     * @var string
     * @deprecated Not used by the Accounts API v3.0 schema; use {@link $individual} instead.
     */
    public $last_name;

    /**
     * The representative's address.
     * [Optional]
     *
     * @var Address
     * @deprecated Not used by the Accounts API v3.0 schema; use {@link $individual} instead.
     */
    public $address;

    /**
     * The representative's identification.
     * [Optional]
     *
     * @var Identification
     * @deprecated Not used by the Accounts API v3.0 schema; use {@link $individual} instead.
     */
    public $identification;

    /**
     * The representative's phone number.
     * [Optional]
     *
     * @var Phone
     * @deprecated Not used by the Accounts API v3.0 schema; use {@link $individual} instead.
     */
    public $phone;

    /**
     * The representative's date of birth.
     * [Optional]
     *
     * @var DateOfBirth
     * @deprecated Not used by the Accounts API v3.0 schema; use {@link $individual} instead.
     */
    public $date_of_birth;

    /**
     * The representative's place of birth.
     * [Optional]
     *
     * @var PlaceOfBirth
     * @deprecated Not used by the Accounts API v3.0 schema; use {@link $individual} instead.
     */
    public $place_of_birth;
}
