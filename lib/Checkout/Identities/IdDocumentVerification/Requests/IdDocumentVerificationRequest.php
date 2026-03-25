<?php

namespace Checkout\Identities\IdDocumentVerification\Requests;

use Checkout\Identities\Entities\DeclaredData;

class IdDocumentVerificationRequest
{
    /**
     * The applicant's unique identifier. (Required)
     *
     * @var string
     */
    public $applicant_id;

    /**
     * Your configuration ID. (Required)
     *
     * @var string
     */
    public $user_journey_id;

    /**
     * The personal details provided by the applicant.
     *
     * @var DeclaredData
     */
    public $declared_data;
}
