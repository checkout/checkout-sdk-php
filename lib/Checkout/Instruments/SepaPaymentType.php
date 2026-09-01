<?php

namespace Checkout\Instruments;

/**
 * The type of payment for a SEPA instrument.
 *
 * The wire values are lowercase. The equivalent Bacs Direct Debit field is capitalized, so do not
 * share one class between the two. Do not use Checkout\Payments\PaymentType either: that class
 * serializes capitalized values and also carries MOTO, Installment and Unscheduled, which SEPA does
 * not allow.
 */
class SepaPaymentType
{
    public static $recurring = "recurring";

    public static $regular = "regular";
}
