<?php

namespace Checkout\Identities\IdentityVerification\Requests;

use Checkout\Identities\Entities\ClientInformation;

class IdentityVerificationAttemptRequest
{
    /**
     * The URL to redirect the applicant to after the attempt. (Required)
     *
     * @var string
     */
    public $redirect_url;

    /**
     * The applicant's details.
     *
     * @var ClientInformation
     */
    public $client_information;
}
