<?php

namespace Checkout\Payments\Setups\Common\Billing;

use Checkout\Common\Address;
use Checkout\Common\EmptyAsObject;

/**
 * The billing details for the payment.
 */
#[EmptyAsObject]
class PaymentSetupBilling
{
    /**
     * A physical address.
     * [Optional]
     * address_line1 and address_line2: max 200 characters; city, state and zip: max 50 characters;
     * country: max 2 characters, in ISO 3166-1 alpha-2 format.
     * @var Address
     */
    public $address;
}
