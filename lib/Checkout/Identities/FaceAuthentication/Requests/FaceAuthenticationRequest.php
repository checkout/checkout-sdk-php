<?php

namespace Checkout\Identities\FaceAuthentication\Requests;

class FaceAuthenticationRequest
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
}
