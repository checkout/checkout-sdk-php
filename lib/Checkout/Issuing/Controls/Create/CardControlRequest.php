<?php

namespace Checkout\Issuing\Controls\Create;

abstract class CardControlRequest
{
    protected function __construct($type)
    {
        $this->control_type = $type;
    }

    /**
     * @var string value of ControlType
     */
    public $control_type;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $target_id;
}
