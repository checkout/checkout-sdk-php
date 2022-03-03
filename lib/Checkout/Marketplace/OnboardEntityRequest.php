<?php

namespace Checkout\Marketplace;

class OnboardEntityRequest
{
    public string $reference;

    public ContactDetails $contact_details;

    public Profile $profile;

    public Company $company;

    public Individual $individual;

}
