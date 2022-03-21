<?php

namespace Checkout\Payments\Four\Sender;

class PaymentCorporateSender extends PaymentSender
{
    public function __construct()
    {
        parent::__construct(PaymentSenderType::$corporate);
    }

    public $company_name;

    // Address
    public $address;

}
