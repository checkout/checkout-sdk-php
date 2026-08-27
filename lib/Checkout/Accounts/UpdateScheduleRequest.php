<?php

namespace Checkout\Accounts;

class UpdateScheduleRequest
{
    /**
     * @var bool
     */
    public $enabled;

    /**
     * @var int
     */
    public $threshold;

    /**
     * The amount, in the minor units of the schedule's currency, to retain in the
     * sub-entity's available balance. Only funds above the balance_minimum are paid out.
     * Applies to SaaS seller (ISV) schedules. Defaults to 0 if not set. Minimum value 0.
     *
     * @var int
     */
    public $balance_minimum;

    /**
     * Indicates whether to carry forward any balance below the configured minimum
     * to the next payout. Applies to SaaS seller (ISV) schedules. Defaults to false if not set.
     *
     * @var bool
     */
    public $carry_forward_enabled;

    /**
     * The ID of the platforms payment instrument used as the payout destination.
     * If included, it must reference a verified payment instrument.
     *
     * @var string
     */
    public $payment_instrument_id;

    /**
     * @var ScheduleRequest
     */
    public $recurrence;
}
