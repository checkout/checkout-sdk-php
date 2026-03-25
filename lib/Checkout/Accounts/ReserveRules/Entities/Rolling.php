<?php

namespace Checkout\Accounts\ReserveRules\Entities;

class Rolling
{
    /**
     * The percentage of captured funds that will be reserved as a collateral balance. (0-100 with up to 2 decimal places) (Required)
     *
     * @var float
     */
    public $percentage;

    /**
     * The length of time the collateral balance will be reserved for. (Required)
     *
     * @var HoldingDuration
     */
    public $holding_duration;
}
