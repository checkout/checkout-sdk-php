<?php

namespace Checkout\Payments\Four\Sender;

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
    public $fist_name;

    /**
     * @var string
     */
    public $last_name;

    /**
     * @var Address
     */
    public $address;

    /**
     * @var Identification
     */
    public $identification;
}
