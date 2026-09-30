<?php

namespace Checkout\Accounts;

use Checkout\Common\Address;
use Checkout\Common\Phone;

/**
 * The personal details of a company representative ("person of interest"), as required by the
 * Accounts API v3.0 schema.
 */
class RepresentativeIndividual
{
    /**
     * The representative's first name.
     * [Required]
     * Length: 2 to 50 characters
     *
     * @var string
     */
    public $first_name;

    /**
     * The representative's middle name. Required if it appears in official documents.
     * [Optional]
     * Length: 2 to 50 characters
     *
     * @var string
     */
    public $middle_name;

    /**
     * The representative's last name.
     * [Required]
     * Length: 2 to 50 characters
     *
     * @var string
     */
    public $last_name;

    /**
     * The representative's date of birth.
     * [Required]
     *
     * @var DateOfBirth
     */
    public $date_of_birth;

    /**
     * The representative's place of birth.
     * [Required]
     *
     * @var PlaceOfBirth
     */
    public $place_of_birth;

    /**
     * The list of citizenships or legal statuses for the representative.
     * [Required] for the US ISV Seller variants only (Accounts API v3.0). Not part of the other
     * v3.0 schemas; leave unset for them.
     *
     * @var array values of Citizenship
     */
    public $citizenships;

    /**
     * The classification of the national identification number provided.
     * [Required] for the US ISV Seller variants only (Accounts API v3.0). Not part of the other
     * v3.0 schemas; leave unset for them.
     * Enum: "ssn", "itin", "passport", "driving_license", "national_id_card", "residence_permit", "other"
     *
     * @var string value of NationalIdType
     */
    public $national_id_type;

    /**
     * The representative's national identification number.
     * [Required] for the US ISV Seller variants; [Optional] for the other v3.0 variants.
     * The format depends on the variant:
     * - US ISV Seller: the number for the national_id_type given. ^[a-zA-Z0-9\-]+$,
     *   Length: 5 to 16 characters.
     * - Other v3.0 variants: a Social Security Number (SSN) or Individual Taxpayer Identification
     *   Number (ITIN), US residents only. ^\d{9}$, Length: 9 characters.
     *
     * @var string
     */
    public $national_id_number;

    /**
     * The representative's personal email address.
     * [Required] for the US ISV Seller variants; [Optional] for the other v3.0 variants.
     * Format: email
     *
     * @var string
     */
    public $email_address;

    /**
     * The representative's phone number.
     * [Required] for the US ISV Seller variants; [Optional] for the other v3.0 variants.
     *
     * @var Phone
     */
    public $phone;

    /**
     * The representative's address.
     * [Required]
     *
     * @var Address
     */
    public $address;
}
