<?php

namespace Checkout\Payments\Four\Request;

class PaymentInstruction
{
    /**
     * @var string
     */
    public $purpose;

    /**
     * @var string
     */
    public $charge_bearer;

    /**
     * @var bool
     */
    public $repair;

    /**
     * @var string value of InstructionScheme
     */
    public $scheme;

    /**
     * @var string
     */
    public $quote_id;
}
