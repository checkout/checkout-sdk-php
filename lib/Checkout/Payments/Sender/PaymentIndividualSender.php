<?php

namespace Checkout\Payments\Sender;

use Checkout\Common\AccountHolderIdentification;
use Checkout\Common\Address;

class PaymentIndividualSender extends PaymentSender
{
    public function __construct()
    {
        parent::__construct(PaymentSenderType::$individual);
    }

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
    public $dob;

    /**
     * @var Address
     */
    public $address;

    /**
     * @var AccountHolderIdentification
     */
    public $identification;

    /**
     * @var string
     */
    public $reference_type;

    /**
     * @var string
     */
    public $source_of_funds;

    /**
     * @var string
     */
    public $date_of_birth;

    /**
     * @var string values of Country
     */
    public $country_of_birth;

    /**
     * @var string values of Country
     */
    public $nationality;

}
