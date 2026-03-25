<?php

namespace Checkout\Accounts\ReserveRules\Requests;

use Checkout\Accounts\ReserveRules\Entities\Rolling;

class UpdateReserveRuleRequest
{
    /**
     * The reserve rule type (Required)
     *
     * @var string
     */
    public $type;

    /**
     * The rolling reserve rule configuration (Required)
     *
     * @var Rolling
     */
    public $rolling;
}
