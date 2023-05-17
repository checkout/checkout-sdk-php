<?php

namespace Checkout\Issuing\Controls\Create;

use Checkout\Issuing\Controls\ControlType;
use Checkout\Issuing\Controls\VelocityLimit;

class VelocityCardControlRequest extends CardControlRequest
{
    public function __construct()
    {
        parent::__construct(ControlType::$velocity_limit);
    }

    /**
     * @var VelocityLimit
     */
    public $velocity_limit;
}
