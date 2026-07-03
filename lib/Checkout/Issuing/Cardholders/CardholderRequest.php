<?php

namespace Checkout\Issuing\Cardholders;

use Checkout\Common\Address;
use Checkout\Common\Phone;

class CardholderRequest
{
    /**
     * The type of cardholder to create.
     * [Required]
     * @var string value of CardholderType
     */
    public $type;

    /**
     * Your reference.
     * [Optional]
     * max 256 characters
     * @var string
     */
    public $reference;

    /**
     * The entity's unique identifier.
     * [Required]
     * ^ent_[a-z0-9]{26}$
     * min 30 characters, max 30 characters
     * @var string
     */
    public $entity_id;

    /**
     * The cardholder's first name.
     * [Optional]
     * min 1 character, max 40 characters
     * @var string
     */
    public $first_name;

    /**
     * The cardholder's middle name.
     * [Optional]
     * min 1 character, max 40 characters
     * @var string
     */
    public $middle_name;

    /**
     * The cardholder's last name.
     * [Optional]
     * min 1 character, max 40 characters
     * @var string
     */
    public $last_name;

    /**
     * The cardholder's email address.
     * [Optional]
     * @var string
     */
    public $email;

    /**
     * The cardholder's mobile phone number.
     * [Optional]
     * @var Phone
     */
    public $phone_number;

    /**
     * The cardholder's date of birth in the YYYY-MM-DD format.
     * [Optional]
     * Format: date (yyyy-MM-dd)
     * @var string
     */
    public $date_of_birth;

    /**
     * The cardholder's billing address.
     * [Optional]
     * @var Address
     */
    public $billing_address;

    /**
     * The cardholder's residency address.
     * [Optional]
     * @var Address
     */
    public $residency_address;
}
