<?php

namespace Checkout\Payments\Request;

class PaymentRoutingAttempt
{
    /**
     * The card scheme to use for the payment attempt.
     * [Optional]
     * Enum: "accel" "amex" "cartes_bancaires" "diners" "discover" "jcb" "mada"
     *       "maestro" "mastercard" "nyce" "omannet" "pulse" "shazam" "star" "upi" "visa"
     * @var string|null $scheme value of PaymentRoutingScheme
     */
    public $scheme;
}
