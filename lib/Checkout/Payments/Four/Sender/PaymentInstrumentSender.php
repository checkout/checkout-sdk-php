<?php

namespace Checkout\Payments\Four\Sender;

class PaymentInstrumentSender extends PaymentSender
{
    public function __construct()
    {
        parent::__construct(PaymentSenderType::$instrument);
    }

}
