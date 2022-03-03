<?php

namespace Checkout\Payments\Four\Request;

class PaymentInstruction
{
    public string $purpose;

    public string $charge_bearer;

    public bool $repair;

    //InstructionScheme
    public string $scheme;

    public string $quote_id;

}
