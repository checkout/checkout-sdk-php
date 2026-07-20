<?php

namespace Checkout\Accounts;

use Checkout\Common\Address;
use Checkout\Common\Phone;

class Representative
{
    /**
     * The representative's personal details, as required by the Accounts API v3.0 schema.
     *
     * @var RepresentativeIndividual
     */
    public $individual;

    /**
     * @var string
     * @deprecated Not used by the Accounts API v3.0 schema; use {@link $individual} instead.
     */
    public $first_name;

    /**
     * @var string
     */
    public $last_name;

    /**
     * @var Address
     */
    public $address;

    /**
     * @var Identification
     */
    public $identification;

    /**
     * @var Phone
     */
    public $phone;

    /**
     * @var DateOfBirth
     */
    public $date_of_birth;

    /**
     * @var PlaceOfBirth
     */
    public $place_of_birth;

    /**
     * @var array values of EntityRoles
     */
    public $roles;

    /**
     * @var OnboardSubEntityDocuments
     */
    public $documents;
}
