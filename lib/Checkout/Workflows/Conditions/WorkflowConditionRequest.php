<?php

namespace Checkout\Workflows\Conditions;

abstract class WorkflowConditionRequest
{
    public $type;

    protected function __construct($type)
    {
        $this->type = $type;
    }
}
