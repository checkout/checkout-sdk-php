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
     * [Required] in the hosted onboarding invite request; [Optional] in the full and lite onboarding
     * variants; not part of the US ISV Seller variants.
     * Format: email
     *
     * @var string
     */
    public $email;
}
