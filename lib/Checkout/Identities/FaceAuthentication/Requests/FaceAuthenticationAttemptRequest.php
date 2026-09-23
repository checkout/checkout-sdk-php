<?php

namespace Checkout\Identities\FaceAuthentication\Requests;

use Checkout\Identities\Entities\ClientInformation;
use Checkout\Identities\Entities\PhoneNumber;

class FaceAuthenticationAttemptRequest
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
     * @var ClientInformation|null
     */
    public $client_information;
}
