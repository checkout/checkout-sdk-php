<?php

namespace Checkout\Identities\FaceAuthentication\Requests;

use Checkout\Identities\Entities\ClientInformation;

class FaceAuthenticationAttemptRequest
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
