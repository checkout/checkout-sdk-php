<?php

namespace Checkout\Identities\Entities;

/**
 * The personal details provided by the applicant for an identity verification.
 * Extends the shared declared data with the fields the identity verification schemas add.
 */
class IdentityDeclaredData extends DeclaredData
{
    /**
     * The applicant's mobile phone number, if sharing the attempt URL via SMS.
     * [Optional]
     * @var PhoneNumber|null
     */
    public $phone_number;

    /**
     * The applicant's email address.
     * [Optional]
     * Format: email
     * Example: hannah.bret@example.com
     * @var string|null
     */
    public $email;

    /**
     * The applicant's address.
     * [Optional]
     * @var IdvAddress|null
     */
    public $address;
}
