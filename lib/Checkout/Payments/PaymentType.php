<?php

namespace Checkout\Payments;

/**
 * The payment type of a payment request.
 * Enum: "Regular" "Recurring" "MOTO" "Installment" "PayLater" "Unscheduled"
 */
class PaymentType
{
    public static $regular = "Regular";
    public static $recurring = "Recurring";
    public static $moto = "MOTO";
    public static $installment = "Installment";
    public static $pay_later = "PayLater";
    public static $unscheduled = "Unscheduled";
}
