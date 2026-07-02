<?php

namespace Checkout\Issuing\Disputes\Entities;

class IssuingDisputeFraudDetails
{
    /**
     * The type of fraud the cardholder is asserting.
     * [Required]
     * Enum: value of IssuingDisputeFraudType
     * @var string
     */
    public $fraud_type;

    /**
     * A description of the fraud circumstances, for internal reference. This is not forwarded to the scheme.
     * [Optional]
     * @var string
     */
    public $description;
}
