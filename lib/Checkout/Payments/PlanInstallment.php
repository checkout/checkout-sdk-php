<?php

namespace Checkout\Payments;

class PlanInstallment extends PaymentPlan
{
    /**
     * If the installment plan has financing.
     * [Optional]
     * default false
     * @var bool|null $financing
     */
    public $financing;

    /**
     * The fixed installment amount agreed for subsequent payments.
     * [Optional]
     * @var string|null $amount
     */
    public $amount;
}
