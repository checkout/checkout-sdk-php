<?php

namespace Checkout\Identities\AddressDocumentVerification\Requests;

use Checkout\Identities\Entities\DeclaredData;

class AddressDocumentVerificationRequest
{
    /**
     * The applicant's unique identifier.
     * [Required]
     * ^aplt_\w+$
     * @var string
     */
    public $applicant_id;

    /**
     * Your configuration ID.
     * [Required]
     * ^usj_[a-z2-7]{26}$
     * @var string
     */
    public $user_journey_id;

    /**
     * The personal details provided by the applicant.
     * [Optional]
     * @var DeclaredData
     */
    public $declared_data;
}
