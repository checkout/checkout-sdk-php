<?php

namespace Checkout\Workflows\Conditions;

abstract class WorkflowConditionRequest
{
    /**
     * @var WorkflowConditionType
     */
    public $type;

    protected function __construct($type)
    {
        $this->type = $type;
    }
}
