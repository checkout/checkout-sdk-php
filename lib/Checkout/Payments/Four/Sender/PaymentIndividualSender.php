<?php

namespace Checkout\Payments\Four\Sender;

class PaymentIndividualSender extends PaymentSender
{
    public function __construct()
    {
        parent::__construct(PaymentSenderType::$individual);
    }

    public $fist_name;

    public $last_name;

    // Address
    public $address;

    // Identification
    public $identification;

}
