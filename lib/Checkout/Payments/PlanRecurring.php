<?php

namespace Checkout\Payments;

class PlanRecurring extends PaymentPlan
{
    /**
     * Specifies whether the amount is fixed or variable for each recurrence.
     * [Optional]
     * Enum: "Fixed" "Variable"
     * @var string|null $amount_variability value of AmountVariabilityType
     */
    public $amount_variability;
}
