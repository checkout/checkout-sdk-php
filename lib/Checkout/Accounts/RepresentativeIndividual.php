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
     * The representative's national identification number.
     * [Optional]
     * Format: region-specific (validated by the API against the sub-entity's country)
     *
     * @var string
     */
    public $national_id_number;

    /**
     * The representative's email address.
     * [Optional]
     * Format: email
     *
     * @var string
     */
    public $email_address;

    /**
     * The representative's phone number.
     * [Optional]
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
