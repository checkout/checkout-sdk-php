<?php

namespace Checkout\Payments;

class PaymentPlan
{
    /**
     * Indicates the minimum number of days between payments.
     * [Optional]
     * @var int|null $days_between_payments
     */
    public $days_between_payments;

    /**
     * Indicates the agreed total number of payments.
     * [Optional]
     * @var int|null $total_number_of_payments
     */
    public $total_number_of_payments;

    /**
     * The number of the current installment payment.
     * [Optional]
     * @var int|null $current_payment_number
     */
    public $current_payment_number;

    /**
     * The date after which no further payments will be performed in the format YYYYMMDD.
     * [Optional]
     * @var string|null $expiry
     */
    public $expiry;

    /**
     * The name of the payment plan. Required when source.type is blik.
     * For Blik merchant-initiated requests using an external partner_agreement_id,
     * this value is used as the Blik Alias Label.
     * [Optional]
     * max 35 characters
     * @var string|null $name
     */
    public $name;

    /**
     * The date on which the first payment will be taken, in YYYYMMDD format.
     * Required when source.type is blik and the recurring agreement is created
     * without an initial payment (amount set to 0).
     * [Optional]
     * @var string|null $start_date
     */
    public $start_date;

    /**
     * The amount to charge for each payment in the plan, in the minor currency unit.
     * Required when source.type is blik, payment_plan.amount_variability is Fixed,
     * and the recurring agreement is created without an initial payment (amount set to 0).
     * [Optional]
     * min 1
     * @var int|null $amount
     */
    public $amount;

    /**
     * Specifies whether the amount is fixed or variable for each recurrence.
     * Used when payment_type is recurring.
     * [Optional]
     * Enum: "Fixed" "Variable"
     * @var string|null $amount_variability value of AmountVariabilityType
     */
    public $amount_variability;

    /**
     * Whether the installment plan has financing.
     * Used when payment_type is installment.
     * [Optional]
     * @var bool|null $financing
     */
    public $financing;
}
