<?php

namespace Checkout\Payments\Four\Sender;

use Checkout\Common\Address;

class PaymentIndividualSender extends PaymentSender
{
    public function __construct()
    {
        parent::__construct(PaymentSenderType::$individual);
    }

    public string $fist_name;

    public string $last_name;

    public Address $address;

    public Identification $identification;

}
