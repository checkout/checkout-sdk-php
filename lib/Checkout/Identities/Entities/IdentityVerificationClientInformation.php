<?php

namespace Checkout\Identities\Entities;

/**
 * The applicant's details for an identity verification attempt.
 * Extends the shared client information with the two fields that only the identity verification
 * attempt accepts. The face authentication attempt must keep using ClientInformation, because its
 * schema does not declare these.
 */
class IdentityVerificationClientInformation extends ClientInformation
{
    /**
     * The country that issued the applicant's identity document.
     * [Optional]
     * Standard: ISO 3166-1 alpha-2 country code
     * ^[A-Z]{2}
     * Example: FR
     * @var string|null values of Checkout\Common\CountryCode
     */
    public $pre_selected_document_issuing_country;

    /**
     * The type of identity document the applicant uses for the attempt.
     * [Optional]
     * Enum: "Driving licence" "ID" "Other" "Passport" "Residence Permit" "Travel Document" "Visa"
     * @var string|null
     */
    public $pre_selected_document_type;
}
