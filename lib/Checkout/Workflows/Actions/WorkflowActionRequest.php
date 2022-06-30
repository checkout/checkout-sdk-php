<?php

namespace Checkout\Workflows\Actions;

abstract class WorkflowActionRequest
{
    /**
     * @var string value of WorkflowActionType
     */
    public $type;

    protected function __construct($type)
    {
        $this->type = $type;
    }
}
