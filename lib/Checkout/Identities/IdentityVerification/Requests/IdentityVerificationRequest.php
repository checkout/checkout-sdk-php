<?php

namespace Checkout\Identities\IdentityVerification\Requests;

use Checkout\Identities\Entities\IdentityDeclaredData;

class IdentityVerificationRequest
{
    /**
     * The applicant's unique identifier.
     *
     * @var string
     */
    public $applicant_id;

    /**
     * The personal details provided by the applicant.
     *
     * [Required]
     * @var IdentityDeclaredData
     */
    public $declared_data;

    /**
     * Your configuration ID.
     *
     * @var string
     */
    public $user_journey_id;
}
