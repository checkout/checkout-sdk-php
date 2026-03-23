<?php

namespace Checkout\Issuing\ControlGroups\Entities;

use Checkout\Issuing\Controls\MccLimit;
use Checkout\Issuing\Controls\VelocityLimit;

class Control
{
    /**
     * The control's type. (Required)
     *
     * @var string
     */
    public $control_type;

    /**
     * @var string
     */
    public $description;

    /**
     * @var MccLimit
     */
    public $mcc_limit;

    /**
     * @var MidLimit
     */
    public $mid_limit;

    /**
     * The velocity limit, which determines how much a target card can spend over a given timeframe.
     *
     * @var VelocityLimit
     */
    public $velocity_limit;
}
