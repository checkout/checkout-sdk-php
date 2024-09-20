<?php

namespace Checkout\Workflows;

class UpdateWorkflowRequest
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var bool
     */
    public $active;

    /**
     * @var array of WorkflowConditionRequest
     */
    public $conditions;

    /**
     * @var array of WorkflowActionRequest
     */
    public $actions;
}
