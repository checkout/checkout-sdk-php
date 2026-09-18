<?php

namespace Checkout\Identities\IdentityVerification\Requests;

use Checkout\Identities\Entities\IdentityVerificationClientInformation;
use Checkout\Identities\Entities\PhoneNumber;

class IdentityVerificationAttemptRequest
{
    /**
     * The URL to redirect the applicant to after the attempt.
     * [Required]
     * Format: uri
     * @var string
     */
    public $redirect_url;

    /**
     * The applicant's mobile phone number, if sharing the attempt URL via SMS.
     * [Optional]
     * @var PhoneNumber|null
     */
    public $phone_number;

    /**
     * The applicant's details.
     * [Optional]
     * @var IdentityVerificationClientInformation|null
     */
    public $client_information;
}
