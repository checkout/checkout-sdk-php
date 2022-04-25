<?php

namespace Checkout\Payments\Four\Sender;

use Checkout\Common\Address;

class PaymentCorporateSender extends PaymentSender
{
    public function __construct()
    {
        parent::__construct(PaymentSenderType::$corporate);
    }

    /**
     * @var string
     */
    public $company_name;

    /**
     * @var Address
     */
    public $address;
}
