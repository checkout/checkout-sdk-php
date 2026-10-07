<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\CashApp;

/**
 * The customer's Cash App profile that they consented to share (response only).
 */
class CashAppCustomerProfile
{
    /**
     * Cash App's identifier for the customer. This is not a Checkout.com customer identifier.
     * [Optional]
     * readOnly
     * @var string
     */
    public $customer_id;

    /**
     * The customer's $Cashtag.
     * [Optional]
     * readOnly
     * @var string
     */
    public $cashtag;

    /**
     * Cash App's reference for the customer profile.
     * [Optional]
     * readOnly
     * @var string
     */
    public $reference_id;

    /**
     * The customer's full name.
     * [Optional]
     * readOnly
     * @var string
     */
    public $full_name;

    /**
     * The customer's given name.
     * [Optional]
     * readOnly
     * @var string
     */
    public $given_name;

    /**
     * The customer's middle name.
     * [Optional]
     * readOnly
     * @var string
     */
    public $middle_name;

    /**
     * The customer's family name.
     * [Optional]
     * readOnly
     * @var string
     */
    public $family_name;

    /**
     * The suffix of the customer's name.
     * [Optional]
     * readOnly
     * @var string
     */
    public $suffix;

    /**
     * The customer's date of birth.
     * [Optional]
     * readOnly
     * Format: date
     * Kept as a string because the provider's format varies (the API example is a date-time,
     * for example 1990-01-01T00:00:00.0000000).
     * @var string
     */
    public $birth_date;

    /**
     * The customer's address.
     * [Optional]
     * readOnly
     * @var CashAppAddress
     */
    public $address;

    /**
     * The customer's phone number.
     * [Optional]
     * readOnly
     * @var string
     */
    public $phone_number;

    /**
     * The customer's email address.
     * [Optional]
     * readOnly
     * @var string
     */
    public $email_address;

    /**
     * The date and time the customer's Cash App account was created.
     * [Optional]
     * readOnly
     * Format: date-time
     * Kept as a string because the provider's format varies (for example
     * 1970-01-18T12:46:04.8000000+00:00, with seven fractional digits).
     * @var string
     */
    public $customer_since;
}
