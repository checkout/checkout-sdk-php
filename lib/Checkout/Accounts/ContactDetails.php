<?php

namespace Checkout\Accounts;

use Checkout\Common\Phone;

/**
 * Contact details of the sub-entity.
 */
class ContactDetails
{
    /**
     * The phone number of the sub-entity.
     * [Required] for every Accounts API v2.0 variant and the US ISV Seller variants; [Optional]
     * for the other v3.0 variants.
     * On v3.0, country_code is required and is the ISO 3166-1 alpha-2 country where the number is
     * registered (for example "FR"), not the dialling code. v2.0 takes number only.
     *
     * @var Phone
     */
    public $phone;

    /**
     * Email addresses for this sub-entity.
     * [Required] for every Accounts API v2.0 variant and the US ISV Seller variants; [Optional]
     * for the other v3.0 variants.
     *
     * @var EntityEmailAddresses
     */
    public $email_addresses;

    /**
     * The details of the user responsible for onboarding the sub-entity.
     * [Required] in the hosted onboarding invite request; [Optional] in the full and lite onboarding
     * variants; not part of the US ISV Seller variants.
     *
     * @var Invitee
     */
    public $invitee;
}
