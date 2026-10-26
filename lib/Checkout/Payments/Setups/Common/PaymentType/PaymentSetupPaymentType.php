<?php

namespace Checkout\Payments\Setups\Common\PaymentType;

/**
 * The type of payment for a Payment Setup.
 *
 * The wire values are lowercase. Do not use Checkout\Payments\PaymentType, which serializes capitalized
 * values and has no pay_later.
 */
class PaymentSetupPaymentType
{
    /** @var string */
    public static $regular = "regular";

    /** @var string */
    public static $recurring = "recurring";

    /** @var string */
    public static $moto = "moto";

    /** @var string */
    public static $installment = "installment";

    /** @var string */
    public static $pay_later = "pay_later";

    /** @var string */
    public static $unscheduled = "unscheduled";
}
