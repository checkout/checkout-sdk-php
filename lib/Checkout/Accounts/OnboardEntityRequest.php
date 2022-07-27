<?php

namespace Checkout\Accounts;

class OnboardEntityRequest
{
    /**
     * @var string
     */
    public $reference;

    /**
     * @var ContactDetails
     */
    public $contact_details;

    /**
     * @var Profile
     */
    public $profile;

    /**
     * @var Company
     */
    public $company;

    /**
     * @var Individual
     */
    public $individual;
}
