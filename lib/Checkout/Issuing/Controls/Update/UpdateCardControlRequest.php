<?php

namespace Checkout\Issuing\Controls\Update;

use Checkout\Issuing\Controls\MccLimit;
use Checkout\Issuing\Controls\VelocityLimit;

class UpdateCardControlRequest
{
    /**
     * @var string
     */
    public $description;

    /**
     * @var VelocityLimit
     */
    public $velocity_limit;

    /**
     * @var MccLimit
     */
    public $mcc_limit;
}
