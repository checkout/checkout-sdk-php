<?php

namespace Checkout\Payments;

class PaymentInstruction
{
    /**
     * The purpose of payment for account funding transactions (AFT).
     * See PaymentPurpose class for available values.
     * @var string value of PaymentPurpose
     */
    public $purpose;
}
