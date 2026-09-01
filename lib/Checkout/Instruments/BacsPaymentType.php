<?php

namespace Checkout\Instruments;

/**
 * The type of payment for a Bacs Direct Debit instrument.
 *
 * The wire values are capitalized, and the specification allows these two values only. The
 * equivalent SEPA field is lowercase in the specification, so do not share one class between the
 * two. Do not use Checkout\Payments\PaymentType either: that class also carries MOTO, Installment
 * and Unscheduled, which Bacs does not allow.
 */
class BacsPaymentType
{
    public static $recurring = "Recurring";

    public static $regular = "Regular";
}
