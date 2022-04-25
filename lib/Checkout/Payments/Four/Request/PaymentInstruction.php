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
     * @var InstructionScheme
     */
    public $scheme;

    /**
     * @var string
     */
    public $quote_id;
}
