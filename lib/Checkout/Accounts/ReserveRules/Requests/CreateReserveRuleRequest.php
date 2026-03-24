<?php

namespace Checkout\Accounts\ReserveRules\Requests;

use Checkout\Accounts\ReserveRules\Entities\Rolling;

class CreateReserveRuleRequest
{
    /**
     * The reserve rule type (Required)
     *
     * @var string
     */
    public $type;

    /**
     * The date and time the reserve rule will come into effect. This must be at least 15 minutes in the future. (Required)
     *
     * @var string
     */
    public $valid_from;

    /**
     * The rolling reserve rule configuration (Required)
     *
     * @var Rolling
     */
    public $rolling;
}
