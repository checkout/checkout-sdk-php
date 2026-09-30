<?php

namespace Checkout\Accounts;

/**
 * The details of the user responsible for onboarding the sub-entity.
 */
class Invitee
{
    /**
     * The main email address for this sub-entity. Despite the spec's wording, this is the address
     * of the invitee, the user responsible for onboarding the sub-entity.
     * [Optional]
     * Format: email
     *
     * @var string
     */
    public $email;
}
