<?php

namespace Checkout\Issuing\Controls\Create;

use Checkout\Issuing\Controls\ControlType;
use Checkout\Issuing\Controls\MccLimit;

class MccCardControlRequest extends CardControlRequest
{
    public function __construct()
    {
        parent::__construct(ControlType::$mcc_limit);
    }

    /**
     * @var MccLimit
     */
    public $mcc_limit;
}
