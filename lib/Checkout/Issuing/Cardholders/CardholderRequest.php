<?php

namespace Checkout\Issuing\Cardholders;

use Checkout\Common\Address;
use Checkout\Common\Phone;

class CardholderRequest
{
    /**
     * @var string value of CardholderType
     */
    public $type;

    /**
     * @var string
     */
    public $reference;

    /**
     * @var string
     */
    public $entity_id;

    /**
     * @var string
     */
    public $first_name;

    /**
     * @var string
     */
    public $middle_name;

    /**
     * @var string
     */
    public $last_name;

    /**
     * @var string
     */
    public $email;

    /**
     * @var Phone
     */
    public $phone_number;

    /**
     * @var string
     */
    public $date_of_birth;

    /**
     * @var Address
     */
    public $billing_address;

    /**
     * @var Address
     */
    public $residency_address;

    /**
     * @var CardholderDocument
     */
    public $document;
}
