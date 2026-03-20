<?php

namespace Checkout\Identities\Entities;

class ClientInformation
{
    /**
     * The applicant's residence country.
     *
     * @var string values of Country
     */
    public $pre_selected_residence_country;

    /**
     * The language you want to use for the user interface.
     *
     * @var string
     */
    public $pre_selected_language;
}
