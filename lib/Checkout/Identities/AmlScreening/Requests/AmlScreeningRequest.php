<?php

namespace Checkout\Identities\AmlScreening\Requests;

use Checkout\Identities\Entities\SearchParameters;

class AmlScreeningRequest
{
    /**
     * The applicant's unique identifier. (Required)
     *
     * @var string
     */
    public $applicant_id;

    /**
     * The screening's configuration details. (Required)
     *
     * @var SearchParameters
     */
    public $search_parameters;

    /**
     * Indicates whether to continue to monitor the applicant's AML status.
     *
     * @var bool
     */
    public $monitored;
}
