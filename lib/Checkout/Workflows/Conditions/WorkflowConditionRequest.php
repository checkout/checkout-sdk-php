<?php

namespace Checkout\Workflows\Conditions;

abstract class WorkflowConditionRequest
{
    /**
     * @var string value of WorkflowConditionType
     */
    public $type;

    protected function __construct($type)
    {
        $this->type = $type;
    }
}
