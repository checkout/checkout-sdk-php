<?php

namespace Checkout\Workflows\Actions;

abstract class WorkflowActionRequest
{
    public $type;

    protected function __construct($type)
    {
        $this->type = $type;
    }
}
