<?php

namespace Checkout\Accounts;

use Checkout\Common\Phone;

/**
 * Contact details of the sub-entity.
 */
class ContactDetails
{
    /**
     * The sub-entity's phone number. On the Accounts API, phone.country_code is the ISO 3166-1
     * alpha-2 country where the number is registered (for example "FR"), not the dialling code.
     * [Optional]
     *
     * @var Phone
     */
    public $phone;

    /**
     * Email addresses for this sub-entity.
     * [Optional]
     *
     * @var EntityEmailAddresses
     */
    public $email_addresses;

    /**
     * The details of the user responsible for onboarding the sub-entity.
     * [Optional] (not part of the US ISV Seller variants)
     *
     * @var Invitee
     */
    public $invitee;
}
