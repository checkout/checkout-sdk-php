<?php

namespace Checkout\Accounts;

use Checkout\Common\Address;

/**
 * Information about the company represented by a sub-entity.
 * Which fields are required depends on the sub-entity's region and onboarding variant.
 */
class Company
{
    /**
     * The company's registration number.
     * [Required]
     * Format: region-specific (validated by the API against the sub-entity's country)
     *
     * @var string
     */
    public $business_registration_number;

    /**
     * The company's business type.
     * [Required]
     * Enum: "individual_or_sole_proprietorship", "limited_company", "public_limited_company",
     * "limited_partnership", "general_partnership", "scottish_limited_partnership",
     * "government_agency", "non_profit_entity", "trust", "club_or_society",
     * "unincorporated_association"
     *
     * @var string value of BusinessType
     */
    public $business_type;

    /**
     * The company's registered legal name.
     * [Required]
     * Length: 2 to 300 characters
     *
     * @var string
     */
    public $legal_name;

    /**
     * The name the company trades under.
     * [Required]
     * Length: 2 to 300 characters
     *
     * @var string
     */
    public $trading_name;

    /**
     * Additional names the company trades under.
     * [Optional]
     * Format: array of string (Accounts API v3.0)
     *
     * @var array of string
     */
    public $additional_trading_names;

    /**
     * Indicates whether the sub-entity is a registered legal entity.
     * [Optional] (Accounts API v3.0)
     *
     * @var bool
     */
    public $is_registered_company;

    /**
     * The date the company was incorporated.
     * [Required] for the full onboarding variants (Accounts API v3.0)
     *
     * @var DateOfIncorporation
     */
    public $date_of_incorporation;

    /**
     * The company's principal (place of business) address.
     * [Required]
     *
     * @var Address
     */
    public $principal_address;

    /**
     * The company's registered address.
     * [Required]
     *
     * @var Address
     */
    public $registered_address;

    /**
     * A company verification document.
     * [Optional]
     *
     * @var EntityDocument
     */
    public $document;

    /**
     * The company's representatives (persons of interest).
     * [Required]
     *
     * @var array of Representative
     */
    public $representatives;

    /**
     * The company's financial details.
     * [Optional]
     *
     * @var EntityFinancialDetails
     */
    public $financial_details;
}
