<?php

namespace Checkout\Payments\Four\Request;

class PaymentInstruction
{
    public $purpose;

    public $charge_bearer;

    public $repair;

    //InstructionScheme
    public $scheme;

    public $quote_id;

}
