<?php

namespace Checkout\Identities\IdentityVerification\Requests;

use Checkout\Identities\Entities\DeclaredData;

class IdentityVerificationAndOpenRequest
{
    /**
     * The personal details provided by the applicant. (Required)
     *
     * @var DeclaredData
     */
    public $declared_data;

    /**
     * The URL to redirect the applicant to after the attempt. (Required)
     *
     * @var string
     */
    public $redirect_url;

    /**
     * Your configuration ID.
     *
     * @var string
     */
    public $user_journey_id;

    /**
     * The applicant's unique identifier.
     *
     * @var string
     */
    public $applicant_id;
}
