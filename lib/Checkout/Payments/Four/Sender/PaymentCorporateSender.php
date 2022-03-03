<?php

namespace Checkout\Payments\Four\Sender;

use Checkout\Common\Address;

class PaymentCorporateSender extends PaymentSender
{
    public function __construct()
    {
        parent::__construct(PaymentSenderType::$corporate);
    }

    public string $company_name;

    public Address $address;

}
