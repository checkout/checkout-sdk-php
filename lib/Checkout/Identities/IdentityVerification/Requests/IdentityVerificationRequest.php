<?php

namespace Checkout\Identities\IdentityVerification\Requests;

use Checkout\Identities\Entities\DeclaredData;

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
     * @var DeclaredData
     */
    public $declared_data;

    /**
     * Your configuration ID.
     *
     * @var string
     */
    public $user_journey_id;
}
