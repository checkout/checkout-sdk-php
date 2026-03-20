<?php

namespace Checkout\Identities\Applicants\Requests;

class ApplicantRequest
{
    /**
     * Your reference for the applicant.
     *
     * @var string
     */
    public $external_applicant_id;

    /**
     * The applicant's email address.
     *
     * @var string
     */
    public $email;

    /**
     * The applicant's full name.
     *
     * @var string
     */
    public $external_applicant_name;
}
