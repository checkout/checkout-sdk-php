<?php

namespace Checkout\Issuing\Cardholders;

use Checkout\Common\Address;
use Checkout\Common\Phone;
use Checkout\Issuing\Cardholders\CardholderDocument;

class UpdateCardholderRequest
{
    /**
     * The cardholder's first name.
     *
     * @var string
     */
    public $first_name;

    /**
     * The cardholder's middle name. To set this field to null, pass null in your request.
     *
     * @var string
     */
    public $middle_name;

    /**
     * The cardholder's last name.
     *
     * @var string
     */
    public $last_name;

    /**
     * The cardholder's date of birth in the YYYY-MM-DD format. To set this field to null, pass null in your request.
     *
     * @var string
     */
    public $date_of_birth;

    /**
     * The cardholder's mobile phone number. This is used in the card tokenization one-time passcode (OTP)
     * challenge flow and in delivery details for physical cards.
     *
     * @var Phone
     */
    public $phone_number;

    /**
     * The cardholder's email address. To set this field to null, pass null in your request.
     *
     * @var string
     */
    public $email;

    /**
     * The cardholder's billing address.
     *
     * @var Address
     */
    public $billing_address;

    /**
     * The cardholder's residency address. To set this field to null, pass null in your request.
     *
     * @var Address
     */
    public $residency_address;

    /**
     * A legal document used to verify the cardholder's identity.
     *
     * @var CardholderDocument
     */
    public $document;
}
